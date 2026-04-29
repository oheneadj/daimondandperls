<?php

declare(strict_types=1);

namespace App\Http\Controllers\Webhooks;

use App\Enums\PaymentStatus;
use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Payment;
use App\Notifications\BookingConfirmedNotification;
use App\Services\InvoiceService;
use App\Services\Payment\PaymentLogger;
use App\Services\Payment\PaymentMethodService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

/*
|--------------------------------------------------------------------------
| Transflow Webhook Controller
|--------------------------------------------------------------------------
|
| Transflow calls this endpoint (server-to-server) after every transaction.
| This route must be CSRF-exempt (configured in bootstrap/app.php).
|
| What happens here:
|   1. Log and store the full callback payload (Transflow recommends this)
|   2. Find the booking using payment_reference = refNo from the callback
|   3. Mark the booking as paid or failed based on responseCode
|   4. Create/update the Payment record
|   5. Send the confirmation notification to the customer
|
| Key difference from Moolre: Transflow sends `refNo` as the reference,
| which maps to booking.payment_reference (the transactionReference we got
| from /request-payments). Moolre sends externalref = booking.reference.
|
| We always return 200 — returning non-2xx causes Transflow to retry.
|
*/

class TransflowWebhookController extends Controller
{
    public function __invoke(
        Request $request,
        InvoiceService $invoiceService,
        PaymentMethodService $paymentMethodService
    ): JsonResponse {
        // Transflow sends a flat JSON payload (not nested under 'data')
        $payload = $request->all();

        Log::info('Transflow Webhook: Received', ['payload' => $payload]);

        // Step 1 — Find the booking using the transactionReference we stored at initiate time
        $reference = (string) ($payload['refNo'] ?? '');

        PaymentLogger::log(
            event: 'webhook',
            gateway: 'transflow',
            direction: 'inbound',
            bookingReference: $reference ?: null,
            level: 'info',
            status: 'received',
            gatewayRef: $reference ?: null,
            rawResponse: $payload,
        );

        if (empty($reference)) {
            Log::warning('Transflow Webhook: Missing refNo in payload');

            return response()->json(['status' => 'ignored', 'message' => 'Missing reference']);
        }

        $booking = Booking::query()->where('payment_reference', $reference)->first();

        if (! $booking) {
            Log::info('Transflow Webhook: Booking not found', ['refNo' => $reference]);

            return response()->json(['status' => 'ignored', 'message' => 'Booking not found']);
        }

        // responseCode '01' (string) = success; anything else = failure
        $responseCode = (string) ($payload['responseCode'] ?? '');

        Log::info('Transflow Webhook: Processing', [
            'booking' => $booking->reference,
            'responseCode' => $responseCode,
            'payment_status' => $booking->payment_status->value,
        ]);

        // Step 2 — Handle the outcome
        if ($responseCode === '01') {
            $this->handleSuccess($booking, $payload, $invoiceService, $paymentMethodService);
        } else {
            $this->handleFailure($booking, $payload);
        }

        return response()->json(['status' => 'success']);
    }

    /**
     * Mark booking as paid and send the confirmation notification.
     * Idempotent — safe to call multiple times for the same booking.
     */
    private function handleSuccess(
        Booking $booking,
        array $payload,
        InvoiceService $invoiceService,
        PaymentMethodService $paymentMethodService
    ): void {
        $alreadyPaid = $booking->payment_status === PaymentStatus::Paid;

        // I always store the latest data so I have an audit trail.
        $booking->update([
            'payment_status' => PaymentStatus::Paid,
            'payment_details' => $payload,
        ]);

        // I save the payment method if the customer is logged in.
        $paymentMethodService->saveFromBooking($booking);

        Log::info('Transflow Webhook: Payment marked PAID', ['booking' => $booking->reference]);

        PaymentLogger::log(
            event: 'webhook-paid',
            gateway: 'transflow',
            direction: 'inbound',
            bookingReference: $booking->reference,
            level: 'info',
            status: 'paid',
            gatewayRef: (string) ($payload['refNo'] ?? ''),
            rawResponse: $payload,
        );

        if ($alreadyPaid) {
            // Duplicate webhook — payment record and notification already handled
            return;
        }

        Payment::updateOrCreate(
            ['booking_id' => $booking->id],
            [
                'gateway' => 'transflow',
                'method' => $this->resolvePaymentMethod($payload),
                'amount' => (float) ($payload['amount'] ?? $booking->total_amount),
                'currency' => 'GHS',
                'status' => 'successful',
                'paid_at' => now(),
                'gateway_reference' => $booking->payment_reference,
                'gateway_response' => json_encode($payload),
            ]
        );

        $booking->customer->notify(new BookingConfirmedNotification(
            $booking,
            $invoiceService->getDownloadUrl($booking)
        ));
    }

    /**
     * Mark the booking as failed and store the callback for auditing.
     */
    private function handleFailure(Booking $booking, array $payload): void
    {
        $booking->update([
            'payment_status' => PaymentStatus::Failed,
            'payment_details' => $payload,
        ]);

        Log::warning('Transflow Webhook: Payment marked FAILED', [
            'booking' => $booking->reference,
            'responseCode' => $payload['responseCode'] ?? 'unknown',
            'responseMessage' => $payload['responseMessage'] ?? '',
        ]);

        PaymentLogger::log(
            event: 'webhook-failed',
            gateway: 'transflow',
            direction: 'inbound',
            bookingReference: $booking->reference,
            level: 'warning',
            status: 'failed',
            gatewayRef: (string) ($payload['refNo'] ?? ''),
            errorCode: (string) ($payload['responseCode'] ?? ''),
            errorMessage: (string) ($payload['responseMessage'] ?? ''),
            rawResponse: $payload,
        );
    }

    /**
     * Determine the payment method from the callback payload.
     * Transflow uses network='CARD' for card payments; otherwise it's mobile_money.
     */
    private function resolvePaymentMethod(array $payload): string
    {
        $network = strtoupper((string) ($payload['network'] ?? ''));

        return $network === 'CARD' ? 'card' : 'mobile_money';
    }
}
