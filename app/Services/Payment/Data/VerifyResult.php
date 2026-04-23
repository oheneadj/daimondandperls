<?php

declare(strict_types=1);

namespace App\Services\Payment\Data;

/*
|--------------------------------------------------------------------------
| VerifyResult
|--------------------------------------------------------------------------
|
| Returned by every gateway's verify() method.
| Used by both the webhook handler and the polling fallback.
|
*/

final class VerifyResult
{
    public function __construct(
        // Whether the payment was confirmed as successful
        public readonly bool $paid,

        // Gateway's transaction reference
        public readonly string $reference = '',

        // Amount the gateway confirmed (in the booking's currency)
        public readonly float $amount = 0.0,

        // Currency code, e.g. 'GHS'
        public readonly string $currency = 'GHS',

        // Raw response from the gateway — useful for storing in payment_details
        public readonly array $raw = [],

        // Human-readable reason for failure (empty when paid)
        public readonly string $failureReason = '',
    ) {}

    // ── Convenience constructors ────────────────────────────────────────────

    public static function confirmed(string $reference, float $amount, string $currency = 'GHS', array $raw = []): self
    {
        return new self(
            paid: true,
            reference: $reference,
            amount: $amount,
            currency: $currency,
            raw: $raw,
        );
    }

    public static function failed(string $reason = '', array $raw = []): self
    {
        return new self(paid: false, failureReason: $reason, raw: $raw);
    }
}
