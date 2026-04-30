<?php

declare(strict_types=1);

namespace App\Services\Payment;

use App\Enums\BookingStatus;
use App\Enums\PaymentStatus;
use App\Models\Booking;
use App\Models\Payment;
use App\Notifications\BookingConfirmedNotification;
use App\Services\InvoiceService;
use App\Services\Payment\Data\VerifyResult;
use Illuminate\Support\Facades\Log;

// I created this service to centralise the "mark booking as paid" logic.
// Before this, the same code lived in both TransflowReturnController and
// TransflowWebhookController. Now both call me instead of duplicating.
class PaymentConfirmationService
{
    public function __construct(
        private readonly InvoiceService $invoiceService,
        private readonly PaymentMethodService $paymentMethodService,
    ) {}

    // I call this whenever I've confirmed a payment is successful via verify().
    // I'm safe to call multiple times — I skip the notification if the booking
    // was already marked Paid (e.g. webhook arrived first).
    public function confirmFromVerify(Booking $booking, VerifyResult $result): void
    {
        // I check this before updating so I know whether to send the notification.
        $alreadyPaid = $booking->payment_status === PaymentStatus::Paid;

        $booking->update([
            'status' => BookingStatus::Confirmed,
            'payment_status' => PaymentStatus::Paid,
            'payment_details' => $result->raw,
        ]);

        // I use updateOrCreate so a duplicate call never creates a second Payment record.
        Payment::updateOrCreate(
            ['booking_id' => $booking->id],
            [
                'gateway' => 'transflow',
                'method' => 'mobile_money',
                'amount' => $result->amount ?: (float) $booking->total_amount,
                'currency' => 'GHS',
                'status' => 'successful',
                'paid_at' => now(),
                'gateway_reference' => $result->reference,
                'gateway_response' => json_encode($result->raw),
            ]
        );

        // I save the MoMo number to the customer's profile so they can reuse it.
        $this->paymentMethodService->saveFromBooking($booking);

        // I only send the confirmation notification once. If the booking was already
        // marked Paid before I was called, the notification was already sent.
        if ($alreadyPaid) {
            Log::info('PaymentConfirmationService: Skipping notification — booking already paid', [
                'booking' => $booking->reference,
            ]);

            return;
        }

        $booking->customer->notify(new BookingConfirmedNotification(
            $booking,
            $this->invoiceService->getDownloadUrl($booking)
        ));

        Log::info('PaymentConfirmationService: Booking confirmed and customer notified', [
            'booking' => $booking->reference,
        ]);
    }
}
