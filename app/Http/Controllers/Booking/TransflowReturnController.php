<?php

declare(strict_types=1);

namespace App\Http\Controllers\Booking;

use App\Enums\PaymentStatus;
use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Payment;
use App\Notifications\BookingConfirmedNotification;
use App\Services\InvoiceService;
use App\Services\Payment\TransflowGateway;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

/*
|--------------------------------------------------------------------------
| Transflow Return Controller
|--------------------------------------------------------------------------
|
| Transflow redirects the customer's browser here after they complete (or
| abandon) payment on the hosted checkout page.
|
| This controller solves the race condition between the webhook and the
| customer's browser redirect — both can arrive in either order.
|
| On success:
|   1. We call verify() to confirm the payment with Transflow
|   2. If confirmed → mark booking Paid (idempotent) → redirect to confirmation
|   3. If not yet confirmed (webhook hasn't fired) → back to payment page
|      with "awaiting" state so Livewire can poll checkPaymentStatus()
|
| On failure:
|   1. Mark booking Failed (idempotent — webhook may have already done it)
|   2. Redirect back to payment page with an error flash message
|
*/

class TransflowReturnController extends Controller
{
    public function __invoke(
        Request $request,
        Booking $booking,
        TransflowGateway $gateway,
        InvoiceService $invoiceService
    ): RedirectResponse {
        $status = $request->query('status', 'failure');

        Log::info('Transflow Return: Customer redirected back', [
            'booking' => $booking->reference,
            'status' => $status,
            'payment_status' => $booking->payment_status->value,
        ]);

        if ($status === 'success') {
            return $this->handleSuccessReturn($booking, $gateway, $invoiceService);
        }

        return $this->handleFailureReturn($booking);
    }

    /**
     * Customer returned from Transflow's page after a successful payment.
     *
     * We verify the payment ourselves rather than trusting the query string,
     * because the webhook may have already handled it — or may not have arrived yet.
     */
    private function handleSuccessReturn(
        Booking $booking,
        TransflowGateway $gateway,
        InvoiceService $invoiceService
    ): RedirectResponse {
        // Already marked paid (webhook beat the redirect) — go straight to confirmation
        if ($booking->payment_status === PaymentStatus::Paid) {
            return redirect()->route('booking.confirmation', ['booking' => $booking->reference]);
        }

        // Webhook hasn't fired yet — verify directly with Transflow
        $reference = (string) ($booking->payment_reference ?? '');

        if (! empty($reference)) {
            $result = $gateway->verify($reference);

            if ($result->paid) {
                // Mark paid ourselves (idempotent if webhook fires later)
                $booking->update([
                    'payment_status' => PaymentStatus::Paid,
                    'payment_details' => $result->raw,
                ]);

                Payment::updateOrCreate(
                    ['booking_id' => $booking->id],
                    [
                        'gateway' => 'transflow',
                        'method' => 'mobile_money',
                        'amount' => $result->amount ?: (float) $booking->total_amount,
                        'currency' => 'GHS',
                        'status' => 'successful',
                        'paid_at' => now(),
                        'gateway_reference' => $reference,
                        'gateway_response' => json_encode($result->raw),
                    ]
                );

                $booking->customer->notify(new BookingConfirmedNotification(
                    $booking,
                    $invoiceService->getDownloadUrl($booking)
                ));

                Log::info('Transflow Return: Payment confirmed via verify()', ['booking' => $booking->reference]);

                return redirect()->route('booking.confirmation', ['booking' => $booking->reference]);
            }
        }

        // Verify didn't confirm yet — send customer back to the awaiting screen
        // The Livewire component will poll checkPaymentStatus() until the webhook fires
        Log::info('Transflow Return: Payment not yet confirmed, returning to awaiting screen', [
            'booking' => $booking->reference,
        ]);

        return redirect()
            ->route('booking.payment', ['booking' => $booking->reference])
            ->with('payment_awaiting', true);
    }

    /**
     * Customer returned from Transflow's page after a failed or cancelled payment.
     */
    private function handleFailureReturn(Booking $booking): RedirectResponse
    {
        // Only update if not already marked failed (webhook may have been first)
        if ($booking->payment_status !== PaymentStatus::Failed) {
            $booking->update(['payment_status' => PaymentStatus::Failed]);
        }

        Log::warning('Transflow Return: Payment failed or cancelled', ['booking' => $booking->reference]);

        return redirect()
            ->route('booking.payment', ['booking' => $booking->reference])
            ->with('error', 'Payment was declined or cancelled. Please try again.');
    }
}
