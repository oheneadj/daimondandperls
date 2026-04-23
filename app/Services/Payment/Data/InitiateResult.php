<?php

declare(strict_types=1);

namespace App\Services\Payment\Data;

/*
|--------------------------------------------------------------------------
| InitiateResult
|--------------------------------------------------------------------------
|
| Returned by every gateway's initiate() method. The UI reads $type to
| decide what to render next — it never checks the gateway name directly.
|
| Types:
|   'prompt_sent'  → MoMo push sent; show the "awaiting payment" screen
|   'otp_required' → Gateway needs the customer's OTP first (Moolre-specific)
|   'redirect'     → Send the customer to $redirectUrl (e.g. Paystack)
|   'error'        → Something failed; show $message to the customer
|
*/

final class InitiateResult
{
    public function __construct(
        // What the UI should do next (see types above)
        public readonly string $type,

        // The gateway's transaction reference (empty on error)
        public readonly string $reference = '',

        // Human-readable message — shown to the customer on OTP or error
        public readonly string $message = '',

        // Redirect URL for gateways that use hosted checkout (e.g. Paystack)
        public readonly ?string $redirectUrl = null,

        // Raw response from the gateway — useful for debugging and logging
        public readonly array $raw = [],
    ) {}

    // ── Convenience constructors ────────────────────────────────────────────

    public static function promptSent(string $reference, array $raw = []): self
    {
        return new self(type: 'prompt_sent', reference: $reference, raw: $raw);
    }

    public static function otpRequired(string $message, array $raw = []): self
    {
        return new self(type: 'otp_required', message: $message, raw: $raw);
    }

    public static function redirect(string $reference, string $url, array $raw = []): self
    {
        return new self(type: 'redirect', reference: $reference, redirectUrl: $url, raw: $raw);
    }

    public static function error(string $message, array $raw = []): self
    {
        return new self(type: 'error', message: $message, raw: $raw);
    }

    // ── Helpers ─────────────────────────────────────────────────────────────

    public function failed(): bool
    {
        return $this->type === 'error';
    }

    public function requiresOtp(): bool
    {
        return $this->type === 'otp_required';
    }

    public function isPromptSent(): bool
    {
        return $this->type === 'prompt_sent';
    }

    public function isRedirect(): bool
    {
        return $this->type === 'redirect';
    }
}
