<?php

namespace App\Services;

use App\Models\Booking;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class MoolrePaymentService
{
    protected string $baseUrl;

    protected string $apiUser;

    protected string $pubKey;

    protected string $merchantId;

    public function __construct()
    {
        $this->baseUrl = config('services.moolre.base_url', 'https://api.moolre.com/open/transact');
        $this->apiUser = config('services.moolre.api_user', '');
        $this->pubKey = config('services.moolre.pub_key', '');
        $this->merchantId = config('services.moolre.merchant_id', ''); // Add MOOLRE_MERCHANT_ID to your .env
    }

    /**
     * Get the configured Http client with the strict Moolre Auth Headers
     */
    protected function client()
    {
        return Http::withHeaders([
            'X-API-USER' => $this->apiUser,
            'X-API-PUBKEY' => $this->pubKey,
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
        ]);
    }

    /**
     * Send the payment prompt to the customer's mobile phone
     *
     * @param  string  $channel  (13 = MTN, 6 = Telecel, 7 = AT)
     */
    public function initiatePayment(Booking $booking, string $channel, string $payerNumber): array
    {
        $payload = [
            'type' => 1,
            'channel' => strval($channel),
            'currency' => 'GHS',
            'payer' => $payerNumber,
            'amount' => number_format((float) $booking->total_amount, 2, '.', ''),
            'externalref' => $booking->reference,
            'otpcode' => '',
            'reference' => '',
            'sessionid' => '',
            'accountnumber' => $this->merchantId,
        ];

        try {
            Log::info('Moolre: Initiating payment', [
                'booking' => $booking->reference,
                'channel' => $channel,
                'payer' => $payerNumber,
                'amount' => $payload['amount'],
            ]);

            $response = $this->client()->post($this->baseUrl.'/payment', $payload);

            $json = $response->json() ?? [];

            Log::info('Moolre: Initiate response', [
                'booking' => $booking->reference,
                'http_status' => $response->status(),
                'response' => $json,
            ]);

            if ($response->failed()) {
                Log::error('Moolre: Initiate payment failed', [
                    'booking' => $booking->reference,
                    'http_status' => $response->status(),
                    'payload' => $payload,
                    'response_body' => $response->body(),
                ]);
            }

            return $json ?: ['status' => false, 'message' => 'Failed to reach payment gateway.'];
        } catch (\Exception $e) {
            Log::error('Moolre: Initiate payment exception', [
                'booking' => $booking->reference,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return ['status' => false, 'message' => 'Network error connecting to Moolre.'];
        }
    }

    /**
     * Re-submit the payment request with the customer's OTP code.
     * Called when a prior initiatePayment() returned TP14.
     *
     * @param  string  $channel  (13 = MTN, 6 = Telecel, 7 = AT)
     */
    public function submitOtp(Booking $booking, string $channel, string $payerNumber, string $otpCode): array
    {
        $payload = [
            'type' => 1,
            'channel' => strval($channel),
            'currency' => 'GHS',
            'payer' => $payerNumber,
            'amount' => number_format((float) $booking->total_amount, 2, '.', ''),
            'externalref' => $booking->reference,
            'otpcode' => $otpCode,
            'reference' => '',
            'sessionid' => '',
            'accountnumber' => $this->merchantId,
        ];

        try {
            Log::info('Moolre: Submitting OTP', [
                'booking' => $booking->reference,
                'channel' => $channel,
                'payer' => $payerNumber,
            ]);

            $response = $this->client()->post($this->baseUrl.'/payment', $payload);

            $json = $response->json() ?? [];

            Log::info('Moolre: OTP submit response', [
                'booking' => $booking->reference,
                'http_status' => $response->status(),
                'response' => $json,
            ]);

            return $json ?: ['status' => false, 'message' => 'Failed to reach payment gateway.'];
        } catch (\Exception $e) {
            Log::error('Moolre: OTP submit exception', [
                'booking' => $booking->reference,
                'error' => $e->getMessage(),
            ]);

            return ['status' => false, 'message' => 'Network error connecting to Moolre.'];
        }
    }

    /**
     * Polling fallback tool: Directly verify the status of a transaction reference
     */
    public function checkStatus(string $externalRef): array
    {
        $payload = [
            'type' => 1,
            'idtype' => '1', // Querying using our externalref
            'id' => $externalRef,
            'accountnumber' => $this->merchantId,
        ];

        try {
            Log::info('Moolre: Checking status', [
                'externalref' => $externalRef,
            ]);

            $response = $this->client()->post($this->baseUrl.'/status', $payload);

            $json = $response->json() ?? [];

            Log::info('Moolre: Status response', [
                'externalref' => $externalRef,
                'http_status' => $response->status(),
                'response' => $json,
            ]);

            return $json ?: ['status' => false, 'message' => 'Failed to reach payment gateway.'];
        } catch (\Exception $e) {
            Log::error('Moolre: Status check exception', [
                'externalref' => $externalRef,
                'error' => $e->getMessage(),
            ]);

            return ['status' => false, 'message' => 'Network error linking to Moolre status.'];
        }
    }
}
