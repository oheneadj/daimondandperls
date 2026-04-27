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

        $loggablePayload = array_diff_key($payload, array_flip(['accountnumber']));

        [$response, $httpStatus, $durationMs] = $this->post('/payment', $payload);

        if ($response === null) {
            PaymentLogger::log(
                event: 'initiate',
                gateway: 'moolre',
                direction: 'outbound',
                bookingReference: $booking->reference,
                level: 'error',
                status: 'failed',
                errorMessage: 'Could not reach the payment gateway.',
                network: $channel,
                payerNumber: $payer,
                rawRequest: $loggablePayload,
                durationMs: $durationMs,
            );

            return InitiateResult::error('Could not reach the payment gateway. Please try again.');
        }

        $code = $response['code'] ?? null;
        $status = (int) ($response['status'] ?? 0);

        if ($code === 'TP14') {
            Log::info('Moolre: OTP required', ['booking' => $booking->reference]);

            PaymentLogger::log(
                event: 'initiate',
                gateway: 'moolre',
                direction: 'outbound',
                bookingReference: $booking->reference,
                level: 'info',
                status: 'otp_required',
                errorCode: 'TP14',
                network: $channel,
                payerNumber: $payer,
                rawRequest: $loggablePayload,
                rawResponse: $response,
                httpStatus: $httpStatus,
                durationMs: $durationMs,
            );

            return InitiateResult::otpRequired(
                message: $response['message'] ?? 'A verification code has been sent to your phone.',
                raw: $response,
            );
        }

        if ($status === 1) {
            $reference = (string) ($response['data'] ?? '');

            Log::info('Moolre: Payment prompt sent', ['booking' => $booking->reference, 'ref' => $reference]);

            PaymentLogger::log(
                event: 'initiate',
                gateway: 'moolre',
                direction: 'outbound',
                bookingReference: $booking->reference,
                level: 'info',
                status: 'pending',
                gatewayRef: $reference,
                network: $channel,
                payerNumber: $payer,
                rawRequest: $loggablePayload,
                rawResponse: $response,
                httpStatus: $httpStatus,
                durationMs: $durationMs,
            );

            return InitiateResult::promptSent(reference: $reference, raw: $response);
        }

        $message = $response['message'] ?? 'Payment initiation failed. Please try again.';

        Log::error('Moolre: Initiate failed', ['booking' => $booking->reference, 'response' => $response]);

        PaymentLogger::log(
            event: 'initiate',
            gateway: 'moolre',
            direction: 'outbound',
            bookingReference: $booking->reference,
            level: 'error',
            status: 'failed',
            errorCode: (string) ($response['code'] ?? ''),
            errorMessage: $message,
            network: $channel,
            payerNumber: $payer,
            rawRequest: $loggablePayload,
            rawResponse: $response,
            httpStatus: $httpStatus,
            durationMs: $durationMs,
        );

        return InitiateResult::error(message: $message, raw: $response);
    }

    /**
     * Submit the OTP the customer received and re-trigger the payment.
     */
    public function submitOtp(Booking $booking, string $channel, string $payer, string $otp): InitiateResult
    {
        Log::info('Moolre: Submitting OTP', ['booking' => $booking->reference]);

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

        $loggablePayload = array_diff_key($payload, array_flip(['accountnumber']));

        [$response, $httpStatus, $durationMs] = $this->post('/payment', $payload);

        if ($response === null) {
            PaymentLogger::log(
                event: 'submit-otp',
                gateway: 'moolre',
                direction: 'outbound',
                bookingReference: $booking->reference,
                level: 'error',
                status: 'failed',
                errorMessage: 'Could not reach the payment gateway.',
                network: $channel,
                payerNumber: $payer,
                rawRequest: $loggablePayload,
                durationMs: $durationMs,
            );

            return InitiateResult::error('Could not reach the payment gateway. Please try again.');
        }

        $code = $response['code'] ?? null;

        if ($code === 'TP17') {
            Log::info('Moolre: OTP accepted, re-initiating', ['booking' => $booking->reference]);

            PaymentLogger::log(
                event: 'submit-otp',
                gateway: 'moolre',
                direction: 'outbound',
                bookingReference: $booking->reference,
                level: 'info',
                status: 'pending',
                errorCode: 'TP17',
                network: $channel,
                payerNumber: $payer,
                rawRequest: $loggablePayload,
                rawResponse: $response,
                httpStatus: $httpStatus,
                durationMs: $durationMs,
            );

            return $this->initiate($booking, ['channel' => $channel, 'payer' => $payer]);
        }

        if ($code === 'TP15') {
            PaymentLogger::log(
                event: 'submit-otp',
                gateway: 'moolre',
                direction: 'outbound',
                bookingReference: $booking->reference,
                level: 'warning',
                status: 'failed',
                errorCode: 'TP15',
                errorMessage: 'Invalid verification code.',
                network: $channel,
                payerNumber: $payer,
                rawRequest: $loggablePayload,
                rawResponse: $response,
                httpStatus: $httpStatus,
                durationMs: $durationMs,
            );

            return InitiateResult::error('Invalid verification code. Please check and try again.', $response);
        }

        $message = $response['message'] ?? 'OTP verification failed. Please try again.';

        Log::error('Moolre: OTP submit failed', ['booking' => $booking->reference, 'code' => $code]);

        PaymentLogger::log(
            event: 'submit-otp',
            gateway: 'moolre',
            direction: 'outbound',
            bookingReference: $booking->reference,
            level: 'error',
            status: 'failed',
            errorCode: (string) ($code ?? ''),
            errorMessage: $message,
            network: $channel,
            payerNumber: $payer,
            rawRequest: $loggablePayload,
            rawResponse: $response,
            httpStatus: $httpStatus,
            durationMs: $durationMs,
        );

        return InitiateResult::error($message, $response);
    }

    /**
     * Check whether a Moolre transaction was paid.
     */
    public function verify(string $reference): VerifyResult
    {
        $payload = [
            'type' => 1,
            'idtype' => '1',
            'id' => $reference,
            'accountnumber' => $this->merchantId,
        ];

        Log::info('Moolre: Verifying payment', ['reference' => $reference]);

        $loggablePayload = array_diff_key($payload, array_flip(['accountnumber']));

        [$response, $httpStatus, $durationMs] = $this->post('/status', $payload);

        if ($response === null) {
            PaymentLogger::log(
                event: 'verify',
                gateway: 'moolre',
                direction: 'outbound',
                bookingReference: $reference,
                level: 'error',
                status: 'failed',
                gatewayRef: $reference,
                errorMessage: 'Could not reach Moolre to verify payment.',
                rawRequest: $loggablePayload,
                durationMs: $durationMs,
            );

            return VerifyResult::failed('Could not reach Moolre to verify payment.');
        }

        $txstatus = (int) ($response['data']['txstatus'] ?? $response['txstatus'] ?? 0);
        $amount = (float) ($response['data']['amount'] ?? 0);

        if ($txstatus === 1) {
            Log::info('Moolre: Payment verified as PAID', ['reference' => $reference]);

            PaymentLogger::log(
                event: 'verify',
                gateway: 'moolre',
                direction: 'outbound',
                bookingReference: $reference,
                level: 'info',
                status: 'paid',
                gatewayRef: $reference,
                rawRequest: $loggablePayload,
                rawResponse: $response,
                httpStatus: $httpStatus,
                durationMs: $durationMs,
            );

            return VerifyResult::confirmed(reference: $reference, amount: $amount, raw: $response);
        }

        Log::info('Moolre: Payment not yet paid', ['reference' => $reference, 'txstatus' => $txstatus]);

        PaymentLogger::log(
            event: 'verify',
            gateway: 'moolre',
            direction: 'outbound',
            bookingReference: $reference,
            level: 'info',
            status: 'pending',
            gatewayRef: $reference,
            rawRequest: $loggablePayload,
            rawResponse: $response,
            httpStatus: $httpStatus,
            durationMs: $durationMs,
        );

        return VerifyResult::failed(raw: $response);
    }

    /**
     * Send a POST request to the Moolre API.
     * Returns [body, http_status, duration_ms] or [null, null, duration_ms] on failure.
     *
     * @param  array<string, mixed>  $payload
     * @return array{0: array<string, mixed>|null, 1: int|null, 2: int}
     */
    private function post(string $endpoint, array $payload): array
    {
        $start = microtime(true);

        try {
            $response = Http::withHeaders([
                'X-API-USER' => $this->apiUser,
                'X-API-PUBKEY' => $this->pubKey,
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
            ])->post($this->baseUrl.$endpoint, $payload);

            $durationMs = (int) round((microtime(true) - $start) * 1000);

            return [$response->json() ?? [], $response->status(), $durationMs];
        } catch (\Exception $e) {
            $durationMs = (int) round((microtime(true) - $start) * 1000);

            Log::error('Moolre: HTTP request failed', [
                'endpoint' => $endpoint,
                'error' => $e->getMessage(),
            ]);

            PaymentLogger::log(
                event: 'http-error',
                gateway: 'moolre',
                direction: 'outbound',
                level: 'error',
                status: 'failed',
                errorMessage: $e->getMessage(),
                durationMs: $durationMs,
            );

            return [null, null, $durationMs];
        }
    }
}
