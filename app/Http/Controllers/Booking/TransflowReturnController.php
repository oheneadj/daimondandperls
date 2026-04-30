<?php

declare(strict_types=1);

namespace App\Http\Controllers\Booking;

use App\Enums\PaymentStatus;
use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Services\CartService;
use App\Services\Payment\PaymentConfirmationService;
use App\Services\Payment\PaymentLogger;
use App\Services\Payment\TransflowGateway;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

// I handle the browser redirect back from Transflow after the customer pays.
// The webhook and this redirect can arrive in either order, so I have to be
// careful not to double-notify the customer.
class TransflowReturnController extends Controller
{
    public function __invoke(
        Request $request,
        Booking $booking,
        TransflowGateway $gateway,
        PaymentConfirmationService $confirmationService,
    ): RedirectResponse {
        $status = $request->query('status', 'failure');

        Log::info('Transflow Return: Customer redirected back', [
            'booking' => $booking->reference,
            'status' => $status,
            'payment_status' => $booking->payment_status->value,
        ]);

        PaymentLogger::log(
            event: 'return',
            gateway: 'transflow',
            direction: 'inbound',
            bookingReference: $booking->reference,
            level: 'info',
            status: $status === 'success' ? 'pending' : 'failed',
            gatewayRef: (string) ($booking->payment_reference ?? ''),
        );

        if ($status === 'success') {
            return $this->handleSuccessReturn($booking, $gateway, $confirmationService);
        }

        return $this->handleFailureReturn($booking);
    }

    // I verify the payment with Transflow on the customer's return.
    // If the webhook already confirmed it, I skip straight to the confirmation page.
    // If not confirmed yet, I send them back to the awaiting screen.
    private function handleSuccessReturn(
        Booking $booking,
        TransflowGateway $gateway,
        PaymentConfirmationService $confirmationService,
    ): RedirectResponse {
        // I go straight to confirmation if the webhook already marked it paid.
        if ($booking->payment_status === PaymentStatus::Paid) {
            app(CartService::class)->clear();

            return redirect()->route('booking.confirmation', ['booking' => $booking->reference]);
        }

        $reference = (string) ($booking->payment_reference ?? '');

        if (! empty($reference)) {
            $result = $gateway->verify($reference);

            if ($result->paid) {
                // I let the service handle all DB updates and the notification.
                $confirmationService->confirmFromVerify($booking, $result);

                app(CartService::class)->clear();

                Log::info('Transflow Return: Payment confirmed via verify()', [
                    'booking' => $booking->reference,
                ]);

                PaymentLogger::log(
                    event: 'return-verified',
                    gateway: 'transflow',
                    direction: 'inbound',
                    bookingReference: $booking->reference,
                    level: 'info',
                    status: 'paid',
                    gatewayRef: $reference,
                );

                return redirect()->route('booking.confirmation', ['booking' => $booking->reference]);
            }
        }

        // I send them to the awaiting screen — the poll will keep checking until
        // the webhook fires and updates the booking status.
        Log::info('Transflow Return: Payment not yet confirmed, returning to awaiting screen', [
            'booking' => $booking->reference,
        ]);

        return redirect()
            ->route('booking.payment', ['booking' => $booking->reference])
            ->with('payment_awaiting', true);
    }

    // I mark the booking failed when the customer cancels or the payment is declined.
    private function handleFailureReturn(Booking $booking): RedirectResponse
    {
        // I only update if the webhook hasn't already marked it failed.
        if ($booking->payment_status !== PaymentStatus::Failed) {
            $booking->update(['payment_status' => PaymentStatus::Failed]);
        }

        Log::warning('Transflow Return: Payment failed or cancelled', [
            'booking' => $booking->reference,
        ]);

        PaymentLogger::log(
            event: 'return-failed',
            gateway: 'transflow',
            direction: 'inbound',
            bookingReference: $booking->reference,
            level: 'warning',
            status: 'failed',
            gatewayRef: (string) ($booking->payment_reference ?? ''),
            errorMessage: 'Payment was declined or cancelled by customer.',
        );

        return redirect()
            ->route('booking.payment', ['booking' => $booking->reference])
            ->with('error', 'Payment was declined or cancelled. Please try again.');
    }
}
