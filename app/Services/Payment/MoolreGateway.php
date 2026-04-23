<?php

declare(strict_types=1);

namespace App\Services\Payment;

use App\Contracts\PaymentGatewayContract;
use App\Models\Booking;
use App\Services\Payment\Data\InitiateResult;
use App\Services\Payment\Data\VerifyResult;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/*
|--------------------------------------------------------------------------
| Moolre Payment Gateway
|--------------------------------------------------------------------------
|
| Moolre is a Ghanaian mobile money aggregator. The payment flow works
| like this:
|
|   1. We call initiate() → Moolre sends a prompt to the customer's phone
|   2a. If the customer's network needs OTP first, Moolre returns TP14
|       → We ask the customer for that OTP, then call submitOtp()
|       → submitOtp() calls initiate() again with the OTP included
|   2b. If no OTP needed, Moolre sends the push notification directly
|   3. Customer approves on their phone
|   4. Moolre hits our webhook → we call verify() to confirm and mark paid
|
| All three methods (initiate, submitOtp, verify) read credentials from
| config/payments.php → never from the database.
|
*/

class MoolreGateway implements PaymentGatewayContract
{
    private string $baseUrl;

    private string $apiUser;

    private string $pubKey;

    private string $merchantId;

    public function __construct()
    {
        $this->baseUrl = config('payments.gateways.moolre.base_url', 'https://api.moolre.com/open/transact');
        $this->apiUser = (string) config('payments.gateways.moolre.api_user', '');
        $this->pubKey = (string) config('payments.gateways.moolre.pub_key', '');
        $this->merchantId = (string) config('payments.gateways.moolre.merchant_id', '');
    }

    /**
     * Start a MoMo payment for this booking.
     *
     * Required context keys:
     *   'channel' → MoMo network code: '13' = MTN, '6' = Telecel, '7' = AirtelTigo
     *   'payer'   → Customer's mobile money number (10 digits, e.g. 0244123456)
     *
     * Optional context keys:
     *   'otp' → OTP code when re-initiating after a TP14 OTP challenge
     *
     * Returns:
     *   InitiateResult::promptSent()   → push notification sent, wait for webhook
     *   InitiateResult::otpRequired()  → Moolre needs OTP first (TP14)
     *   InitiateResult::error()        → something went wrong
     *
     * @param  array<string, mixed>  $context
     */
    public function initiate(Booking $booking, array $context = []): InitiateResult
    {
        $channel = (string) ($context['channel'] ?? '');
        $payer = (string) ($context['payer'] ?? '');
        $otp = (string) ($context['otp'] ?? '');

        $payload = [
            'type' => 1,
            'channel' => $channel,
            'currency' => 'GHS',
            'payer' => $payer,
            'amount' => number_format((float) $booking->total_amount, 2, '.', ''),
            'externalref' => $booking->reference,
            'otpcode' => $otp,
            'reference' => '',
            'sessionid' => '',
            'accountnumber' => $this->merchantId,
        ];

        Log::info('Moolre: Initiating payment', [
            'booking' => $booking->reference,
            'channel' => $channel,
            'payer' => $payer,
            'amount' => $payload['amount'],
            'has_otp' => ! empty($otp),
        ]);

        $response = $this->post('/payment', $payload);

        if ($response === null) {
            return InitiateResult::error('Could not reach the payment gateway. Please try again.');
        }

        $code = $response['code'] ?? null;
        $status = (int) ($response['status'] ?? 0);

        // TP14 → Moolre requires OTP from the customer before charging
        if ($code === 'TP14') {
            Log::info('Moolre: OTP required', ['booking' => $booking->reference]);

            return InitiateResult::otpRequired(
                message: $response['message'] ?? 'A verification code has been sent to your phone.',
                raw: $response,
            );
        }

        // status=1 → push notification sent successfully
        if ($status === 1) {
            $reference = (string) ($response['data'] ?? '');

            Log::info('Moolre: Payment prompt sent', ['booking' => $booking->reference, 'ref' => $reference]);

            return InitiateResult::promptSent(reference: $reference, raw: $response);
        }

        // Anything else is an error
        $message = $response['message'] ?? 'Payment initiation failed. Please try again.';
        Log::error('Moolre: Initiate failed', ['booking' => $booking->reference, 'response' => $response]);

        return InitiateResult::error(message: $message, raw: $response);
    }

    /**
     * Submit the OTP the customer received and re-trigger the payment.
     *
     * This is Moolre-specific — other gateways don't need this step.
     * Called by CheckoutPayment when $result->requiresOtp() is true.
     *
     * Returns the same InitiateResult types as initiate() so the UI
     * can handle the response the same way.
     */
    public function submitOtp(Booking $booking, string $channel, string $payer, string $otp): InitiateResult
    {
        Log::info('Moolre: Submitting OTP', ['booking' => $booking->reference]);

        // Moolre's OTP flow: re-send the payment request with the OTP attached.
        // Their API code TP17 means OTP verified — we must then re-initiate to trigger the push.
        $payload = [
            'type' => 1,
            'channel' => $channel,
            'currency' => 'GHS',
            'payer' => $payer,
            'amount' => number_format((float) $booking->total_amount, 2, '.', ''),
            'externalref' => $booking->reference,
            'otpcode' => $otp,
            'reference' => '',
            'sessionid' => '',
            'accountnumber' => $this->merchantId,
        ];

        $response = $this->post('/payment', $payload);

        if ($response === null) {
            return InitiateResult::error('Could not reach the payment gateway. Please try again.');
        }

        $code = $response['code'] ?? null;

        // TP17 → OTP accepted, now re-initiate without OTP to fire the actual prompt
        if ($code === 'TP17') {
            Log::info('Moolre: OTP accepted, re-initiating', ['booking' => $booking->reference]);

            return $this->initiate($booking, ['channel' => $channel, 'payer' => $payer]);
        }

        // TP15 → wrong OTP entered
        if ($code === 'TP15') {
            return InitiateResult::error('Invalid verification code. Please check and try again.', $response);
        }

        $message = $response['message'] ?? 'OTP verification failed. Please try again.';
        Log::error('Moolre: OTP submit failed', ['booking' => $booking->reference, 'code' => $code]);

        return InitiateResult::error($message, $response);
    }

    /**
     * Check whether a Moolre transaction was paid.
     *
     * Called by the webhook controller after receiving a callback,
     * and optionally by the polling fallback in CheckoutPayment.
     */
    public function verify(string $reference): VerifyResult
    {
        $payload = [
            'type' => 1,
            'idtype' => '1', // query by our own externalref
            'id' => $reference,
            'accountnumber' => $this->merchantId,
        ];

        Log::info('Moolre: Verifying payment', ['reference' => $reference]);

        $response = $this->post('/status', $payload);

        if ($response === null) {
            return VerifyResult::failed('Could not reach Moolre to verify payment.');
        }

        // txstatus=1 means confirmed paid
        $txstatus = (int) ($response['data']['txstatus'] ?? $response['txstatus'] ?? 0);
        $amount = (float) ($response['data']['amount'] ?? 0);

        if ($txstatus === 1) {
            Log::info('Moolre: Payment verified as PAID', ['reference' => $reference]);

            return VerifyResult::confirmed(
                reference: $reference,
                amount: $amount,
                raw: $response,
            );
        }

        Log::info('Moolre: Payment not yet paid', ['reference' => $reference, 'txstatus' => $txstatus]);

        return VerifyResult::failed(raw: $response);
    }

    /**
     * Build and send a POST request to the Moolre API.
     * Returns the decoded JSON body, or null on network failure.
     *
     * @param  array<string, mixed>  $payload
     * @return array<string, mixed>|null
     */
    private function post(string $endpoint, array $payload): ?array
    {
        try {
            $response = Http::withHeaders([
                'X-API-USER' => $this->apiUser,
                'X-API-PUBKEY' => $this->pubKey,
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
            ])->post($this->baseUrl.$endpoint, $payload);

            return $response->json() ?? [];
        } catch (\Exception $e) {
            Log::error('Moolre: HTTP request failed', [
                'endpoint' => $endpoint,
                'error' => $e->getMessage(),
            ]);

            return null;
        }
    }
}
