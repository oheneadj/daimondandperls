<?php

namespace App\Http\Controllers;

use App\Enums\PaymentStatus;
use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class MoolreWebhookController extends Controller
{
    public function __invoke(Request $request)
    {
        $payload = $request->all();
        $data = $request->input('data', []);

        Log::info('Moolre Webhook: Received', ['payload' => $payload]);

        $secret = $data['secret'] ?? '';
        $expectedSecret = trim(config('services.moolre.webhook_secret', ''));

        if (! empty($expectedSecret) && $secret !== $expectedSecret) {
            Log::warning('Moolre Webhook: Secret mismatch', [
                'received_secret' => substr($secret, 0, 4).'****',
                'externalref' => $data['externalref'] ?? 'N/A',
            ]);

            return response()->json(['status' => 'ignored', 'message' => 'Invalid signature'], 401);
        }

        /** @var \App\Models\Booking|null $booking */
        $booking = Booking::query()
            ->where(['reference' => (string) ($data['externalref'] ?? '')])
            ->first();

        if (! $booking) {
            Log::info('Moolre Webhook: Booking not found', ['reference' => $data['externalref'] ?? '']);

            return response()->json(['status' => 'ignored', 'message' => 'Booking not found'], 200);
        }

        $txstatus = (int) ($data['txstatus'] ?? 0);

        Log::info('Moolre Webhook: Processing', [
            'booking' => $booking->reference,
            'txstatus' => $txstatus,
            'current_payment_status' => $booking->payment_status->value,
        ]);

        if ($txstatus === 1) {
            $booking->update([
                'payment_status' => PaymentStatus::Paid,
                'payment_details' => $payload,
            ]);
            Log::info('Moolre Webhook: Payment marked as PAID', ['booking' => $booking->reference]);
        } elseif ($txstatus === 2) {
            $booking->update([
                'payment_status' => PaymentStatus::Failed,
                'payment_details' => $payload,
            ]);
            Log::warning('Moolre Webhook: Payment marked as FAILED', ['booking' => $booking->reference]);
        } else {
            Log::info('Moolre Webhook: Unhandled txstatus', ['booking' => $booking->reference, 'txstatus' => $txstatus]);
        }

        return response()->json(['status' => 'success'], 200);
    }
}
