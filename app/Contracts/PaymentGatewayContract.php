<?php

declare(strict_types=1);

namespace App\Contracts;

use App\Models\Booking;
use App\Services\Payment\Data\InitiateResult;
use App\Services\Payment\Data\VerifyResult;

/*
|--------------------------------------------------------------------------
| Payment Gateway Contract
|--------------------------------------------------------------------------
|
| Every payment gateway (Moolre, Paystack, etc.) must implement this.
| The rest of the app only talks to this interface — it never cares
| which gateway is actually running underneath.
|
| How to add a new gateway:
|   1. Create App\Services\Payment\YourGateway
|   2. Implement these two methods
|   3. Register it in PaymentManager::createYourGatewayDriver()
|
*/

interface PaymentGatewayContract
{
    /**
     * Start a payment for the given booking.
     *
     * The $context array carries gateway-specific inputs (e.g. MoMo network
     * and phone number for Moolre, nothing extra for Paystack since it
     * just returns a redirect URL).
     *
     * Always returns an InitiateResult — check $result->type to know
     * what the UI should do next:
     *   'prompt_sent'  → MoMo prompt sent, poll for webhook confirmation
     *   'otp_required' → Gateway needs an OTP before charging (Moolre)
     *   'redirect'     → Redirect the customer to $result->redirectUrl (Paystack)
     *   'error'        → Something went wrong — show $result->message
     *
     * @param  array<string, mixed>  $context
     */
    public function initiate(Booking $booking, array $context = []): InitiateResult;

    /**
     * Check whether a transaction was paid.
     *
     * Called by the webhook handler and optionally by the polling fallback.
     * Returns a VerifyResult with $result->paid === true when confirmed.
     */
    public function verify(string $reference): VerifyResult;
}
