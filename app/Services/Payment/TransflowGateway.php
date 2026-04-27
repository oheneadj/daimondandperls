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
| Transflow Payment Gateway
|--------------------------------------------------------------------------
|
| Transflow (by ITConsortium) is a Ghanaian payment aggregator that supports
| both MoMo (all networks) and card payments through a hosted checkout page.
|
| The flow is redirect-based — completely different from Moolre:
|
|   1. We call initiate() → Transflow returns a hosted checkout URL
|   2. We redirect the customer to that URL
|   3. Customer picks MoMo or card and pays on Transflow's page
|   4. Transflow hits our webhook (TransflowWebhookController) with the result
|   5. Transflow also redirects the customer back to successRedirectUrl or
|      failureRedirectUrl — handled by TransflowReturnController
|
| Auth: apiKey, transflowId, and merchantProductId are sent in every request
| body (not headers). Credentials come from config/payments.php → .env only.
|
| UAT vs Live: the base_url is auto-selected from APP_ENV (see config).
| Override with TRANSFLOW_BASE_URL in .env to force a specific endpoint.
|
*/

class TransflowGateway implements PaymentGatewayContract
{
    private string $baseUrl;

    private string $apiKey;

    private string $transflowId;

    private string $merchantProductId;

    public function __construct()
    {
        $this->baseUrl = (string) config('payments.gateways.transflow.base_url', '');
        $this->apiKey = (string) config('payments.gateways.transflow.api_key', '');
        $this->transflowId = (string) config('payments.gateways.transflow.transflow_id', '');
        $this->merchantProductId = (string) config('payments.gateways.transflow.merchant_product_id', '');
    }

    /**
     * Initiate a payment by requesting a hosted checkout URL from Transflow.
     *
     * Returns InitiateResult::redirect() with the checkout URL on success.
     * The caller should redirect the customer's browser to $result->redirectUrl.
     *
     * @param  array<string, mixed>  $context
     */
    public function initiate(Booking $booking, array $context = []): InitiateResult
    {
        $payload = [
            'fullName' => $booking->customer->name,
            'email' => $booking->customer->email,
            'narration' => 'Payment for booking '.$booking->reference,
            'amount' => (float) $booking->total_amount,
            'currency' => 'GHS',
            'apiKey' => $this->apiKey,
            'transflowId' => $this->transflowId,
            'merchantProductId' => $this->merchantProductId,
            'successRedirectUrl' => route('booking.payment.return', ['booking' => $booking->reference, 'status' => 'success']),
            'failureRedirectUrl' => route('booking.payment.return', ['booking' => $booking->reference, 'status' => 'failure']),
            'callbackUrl' => route('webhooks.transflow'),
            'pageTitle' => config('app.name'),
        ];

        if (! empty($context['payment_method'])) {
            $payload['paymentMethod'] = $context['payment_method'];
        }
        if (! empty($context['msisdn'])) {
            $payload['msisdn'] = $context['msisdn'];
        }
        if (! empty($context['network'])) {
            $payload['network'] = $context['network'];
        }

        Log::info('Transflow: Initiating payment', [
            'booking' => $booking->reference,
            'amount' => $payload['amount'],
        ]);

        // Strip credentials from the logged request
        $loggablePayload = array_diff_key($payload, array_flip(['apiKey', 'transflowId', 'merchantProductId']));

        [$response, $httpStatus, $durationMs] = $this->post('/request-payments', $payload);

        if ($response === null) {
            PaymentLogger::log(
                event: 'initiate',
                gateway: 'transflow',
                direction: 'outbound',
                bookingReference: $booking->reference,
                level: 'error',
                status: 'failed',
                errorMessage: 'Could not reach the payment gateway.',
                network: $context['network'] ?? null,
                payerNumber: $context['msisdn'] ?? null,
                rawRequest: $loggablePayload,
                durationMs: $durationMs,
            );

            return InitiateResult::error('Could not reach the payment gateway. Please try again.');
        }

        if (($response['responseCode'] ?? null) !== 200) {
            $message = $response['responseMessage'] ?? 'Payment initiation failed. Please try again.';

            Log::error('Transflow: Initiate failed', ['booking' => $booking->reference, 'response' => $response]);

            PaymentLogger::log(
                event: 'initiate',
                gateway: 'transflow',
                direction: 'outbound',
                bookingReference: $booking->reference,
                level: 'error',
                status: 'failed',
                errorMessage: $message,
                network: $context['network'] ?? null,
                payerNumber: $context['msisdn'] ?? null,
                rawRequest: $loggablePayload,
                rawResponse: $response,
                httpStatus: $httpStatus,
                durationMs: $durationMs,
            );

            return InitiateResult::error(message: $message, raw: $response);
        }

        $reference = (string) ($response['data']['transactionReference'] ?? '');
        $url = (string) ($response['data']['checkoutUrl'] ?? '');

        Log::info('Transflow: Checkout URL received', ['booking' => $booking->reference, 'reference' => $reference]);

        PaymentLogger::log(
            event: 'initiate',
            gateway: 'transflow',
            direction: 'outbound',
            bookingReference: $booking->reference,
            level: 'info',
            status: 'pending',
            gatewayRef: $reference,
            network: $context['network'] ?? null,
            payerNumber: $context['msisdn'] ?? null,
            rawRequest: $loggablePayload,
            rawResponse: $response,
            httpStatus: $httpStatus,
            durationMs: $durationMs,
        );

        return InitiateResult::redirect(reference: $reference, url: $url, raw: $response);
    }

    /**
     * Verify whether a Transflow transaction was paid.
     *
     * Called by TransflowReturnController when the customer returns to our site.
     */
    public function verify(string $reference): VerifyResult
    {
        $payload = [
            'transactionReference' => $reference,
            'apiKey' => $this->apiKey,
            'transflowId' => $this->transflowId,
            'merchantProductId' => $this->merchantProductId,
        ];

        Log::info('Transflow: Verifying payment', ['reference' => $reference]);

        [$response, $httpStatus, $durationMs] = $this->post('/check-transaction-status', $payload);

        if ($response === null) {
            PaymentLogger::log(
                event: 'verify',
                gateway: 'transflow',
                direction: 'outbound',
                bookingReference: $reference,
                level: 'error',
                status: 'failed',
                gatewayRef: $reference,
                errorMessage: 'Could not reach Transflow to verify payment.',
                durationMs: $durationMs,
            );

            return VerifyResult::failed('Could not reach Transflow to verify payment.');
        }

        $responseCode = (string) ($response['data']['responseCode'] ?? '');
        $amount = (float) ($response['data']['amount'] ?? 0);

        if ($responseCode === '01') {
            Log::info('Transflow: Payment verified as PAID', ['reference' => $reference]);

            PaymentLogger::log(
                event: 'verify',
                gateway: 'transflow',
                direction: 'outbound',
                bookingReference: $reference,
                level: 'info',
                status: 'paid',
                gatewayRef: $reference,
                rawResponse: $response,
                httpStatus: $httpStatus,
                durationMs: $durationMs,
            );

            return VerifyResult::confirmed(reference: $reference, amount: $amount, raw: $response);
        }

        Log::info('Transflow: Payment not yet confirmed', ['reference' => $reference, 'responseCode' => $responseCode]);

        PaymentLogger::log(
            event: 'verify',
            gateway: 'transflow',
            direction: 'outbound',
            bookingReference: $reference,
            level: 'info',
            status: 'pending',
            gatewayRef: $reference,
            rawResponse: $response,
            httpStatus: $httpStatus,
            durationMs: $durationMs,
        );

        return VerifyResult::failed(raw: $response);
    }

    /**
     * Send a POST request to the Transflow API.
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
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
            ])->post($this->baseUrl.$endpoint, $payload);

            $durationMs = (int) round((microtime(true) - $start) * 1000);

            return [$response->json() ?? [], $response->status(), $durationMs];
        } catch (\Exception $e) {
            $durationMs = (int) round((microtime(true) - $start) * 1000);

            Log::error('Transflow: HTTP request failed', [
                'endpoint' => $endpoint,
                'error' => $e->getMessage(),
            ]);

            PaymentLogger::log(
                event: 'http-error',
                gateway: 'transflow',
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
