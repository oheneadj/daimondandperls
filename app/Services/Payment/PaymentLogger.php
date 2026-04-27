<?php

declare(strict_types=1);

namespace App\Services\Payment;

use App\Models\PaymentLog;

class PaymentLogger
{
    /**
     * Write a payment event to the payment_logs table.
     *
     * @param  array<string, mixed>|null  $rawRequest
     * @param  array<string, mixed>|null  $rawResponse
     */
    public static function log(
        string $event,
        string $gateway,
        string $direction,
        ?string $bookingReference = null,
        ?string $level = 'info',
        ?string $status = null,
        ?string $gatewayRef = null,
        ?string $errorCode = null,
        ?string $errorMessage = null,
        ?string $network = null,
        ?string $payerNumber = null,
        ?array $rawRequest = null,
        ?array $rawResponse = null,
        ?int $httpStatus = null,
        ?int $durationMs = null,
        ?string $ipAddress = null,
        ?int $paymentId = null,
    ): void {
        PaymentLog::create([
            'payment_id' => $paymentId,
            'gateway' => $gateway,
            'direction' => $direction,
            'event' => $event,
            'booking_reference' => $bookingReference,
            'level' => $level,
            'status' => $status,
            'gateway_ref' => $gatewayRef,
            'error_code' => $errorCode,
            'error_message' => $errorMessage,
            'network' => $network,
            'payer_number' => $payerNumber,
            'raw_request' => $rawRequest,
            'raw_response' => $rawResponse,
            'http_status' => $httpStatus,
            'duration_ms' => $durationMs,
            'ip_address' => $ipAddress,
            'created_at' => now(),
        ]);
    }
}
