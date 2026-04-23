<?php

declare(strict_types=1);

namespace App\Http\Controllers\Webhooks;

use App\Enums\PaymentStatus;
use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Payment;
use App\Notifications\BookingConfirmedNotification;
use App\Services\InvoiceService;
use App\Services\Payment\MoolreGateway;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

/*
|--------------------------------------------------------------------------
| Moolre Webhook Controller
|--------------------------------------------------------------------------
|
| Moolre calls this endpoint after every transaction outcome.
| This route must be CSRF-exempt (configured in bootstrap/app.php).
|
| What happens here:
|   1. Verify the webhook secret so we know it's really from Moolre
|   2. Find the booking using the externalref we sent them
|   3. Mark the booking as paid or failed based on txstatus
|   4. Create/update the Payment record
|   5. Send the confirmation notification to the customer
|
| We always return 200 to Moolre even for "ignored" events —
| returning non-2xx would cause Moolre to keep retrying.
|
*/

class MoolreWebhookController extends Controller
{
    public function __invoke(Request $request, InvoiceService $invoiceService, MoolreGateway $gateway): JsonResponse
    {
        $payload = $request->all();
        $data = $request->input('data', []);

        Log::info('Moolre Webhook: Received', ['payload' => $payload]);

        // Step 1 — Verify the webhook secret
        if (! $this->isValidSecret($data)) {
            Log::warning('Moolre Webhook: Invalid secret', [
                'externalref' => $data['externalref'] ?? 'N/A',
            ]);

            // Return 200 so Moolre doesn't keep retrying invalid requests
            return response()->json(['status' => 'ignored', 'message' => 'Invalid signature']);
        }

        // Step 2 — Find the booking
        $reference = (string) ($data['externalref'] ?? '');
        $booking = Booking::query()->where('reference', $reference)->first();

        if (! $booking) {
            Log::info('Moolre Webhook: Booking not found', ['reference' => $reference]);

            return response()->json(['status' => 'ignored', 'message' => 'Booking not found']);
        }

        $txstatus = (int) ($data['txstatus'] ?? 0);

        Log::info('Moolre Webhook: Processing', [
            'booking' => $booking->reference,
            'txstatus' => $txstatus,
            'current_payment_status' => $booking->payment_status->value,
        ]);

        // Step 3 — Handle the outcome
        match ($txstatus) {
            1 => $this->handleSuccess($booking, $payload, $invoiceService),
            2 => $this->handleFailure($booking, $payload),
            default => Log::info('Moolre Webhook: Unhandled txstatus', [
                'booking' => $booking->reference,
                'txstatus' => $txstatus,
            ]),
        };

        return response()->json(['status' => 'success']);
    }

    /**
     * Mark booking as paid and send the confirmation notification.
     * Idempotent — safe to call multiple times for the same booking.
     */
    private function handleSuccess(Booking $booking, array $payload, InvoiceService $invoiceService): void
    {
        // Guard against duplicate webhook calls for the same booking
        $alreadyPaid = $booking->payment_status === PaymentStatus::Paid;

        $booking->update([
            'payment_status' => PaymentStatus::Paid,
            'payment_details' => $payload,
        ]);

        Log::info('Moolre Webhook: Payment marked PAID', ['booking' => $booking->reference]);

        if ($alreadyPaid) {
            // Webhook was a duplicate — payment record and notification already handled
            return;
        }

        // Create the Payment record
        Payment::updateOrCreate(
            ['booking_id' => $booking->id],
            [
                'gateway' => 'moolre',
                'method' => 'mobile_money',
                'amount' => $booking->total_amount,
                'currency' => 'GHS',
                'status' => 'successful',
                'paid_at' => now(),
                'gateway_reference' => $booking->payment_reference,
                'gateway_response' => json_encode($payload),
            ]
        );

        // Notify the customer
        $booking->customer->notify(new BookingConfirmedNotification(
            $booking,
            $invoiceService->getDownloadUrl($booking)
        ));
    }

    /**
     * Mark the booking as failed.
     */
    private function handleFailure(Booking $booking, array $payload): void
    {
        $booking->update([
            'payment_status' => PaymentStatus::Failed,
            'payment_details' => $payload,
        ]);

        Log::warning('Moolre Webhook: Payment marked FAILED', ['booking' => $booking->reference]);
    }

    /**
     * Check the webhook secret matches what we configured.
     *
     * @param  array<string, mixed>  $data
     */
    private function isValidSecret(array $data): bool
    {
        $expectedSecret = trim((string) config('payments.gateways.moolre.webhook_secret', ''));

        // If we haven't configured a secret, let all requests through (dev mode)
        if (empty($expectedSecret)) {
            return true;
        }

        return ($data['secret'] ?? '') === $expectedSecret;
    }
}
