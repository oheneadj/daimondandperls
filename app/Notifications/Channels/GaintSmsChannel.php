<?php

namespace App\Notifications\Channels;

use App\Models\SmsLog;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class GaintSmsChannel
{
    /**
     * Send the given notification.
     */
    public function send(object $notifiable, Notification $notification): void
    {
        if (! method_exists($notification, 'toGaintSms')) {
            return;
        }

        $message = $notification->toGaintSms($notifiable);

        $phoneNumber = $notifiable->routeNotificationFor('gaintsms')
            ?? $notifiable->phone
            ?? null;

        if (! $phoneNumber) {
            Log::warning('GaintSmsChannel: No phone number found for notifiable.');

            return;
        }

        // Clean phone number (optional: ensure it starts with 0 or country code)
        // Giant SMS example uses '02xxxxxx01'
        $cleanedPhone = preg_replace('/[^0-9\+]/', '', $phoneNumber);

        $senderId = config('services.gaintsms.sender_id');
        $apiToken = config('services.gaintsms.api_token');

        if (! $senderId || ! $apiToken) {
            Log::error('GaintSmsChannel: Missing Giant SMS credentials.');

            return;
        }

        try {
            $response = Http::withHeaders([
                'Authorization' => 'Basic '.$apiToken,
            ])->asForm()->post('https://api.giantsms.com/api/v1/send', [
                'from' => $senderId,
                'to' => $cleanedPhone,
                'msg' => $message,
            ]);

            $status = 'failed';
            $messageId = null;

            if ($response->successful()) {
                $data = $response->json();
                if (isset($data['status']) && $data['status'] === true) {
                    $status = 'sent';
                    $messageId = $data['data']['message_id'] ?? null;
                } else {
                    Log::error('GaintSmsChannel: API returned error flag.', ['response' => $response->body()]);
                }
            } else {
                Log::error('GaintSmsChannel: HTTP Request Failed.', ['status' => $response->status(), 'response' => $response->body()]);
            }

            // In case we want to associate it with a specific booking later,
            // the notification class can define a `getBookingId` method.
            $bookingId = method_exists($notification, 'getBookingId') ? $notification->getBookingId() : null;

            SmsLog::create([
                'booking_id' => $bookingId,
                'message_id' => $messageId,
                'to' => $cleanedPhone,
                'message' => $message,
                'status' => $status,
                'response' => $response->json(),
            ]);

        } catch (\Exception $e) {
            Log::error('GaintSmsChannel: Exception thrown.', ['exception' => $e->getMessage()]);

            // Log the failed attempt in db
            SmsLog::create([
                'to' => $cleanedPhone,
                'message' => $message,
                'status' => 'failed',
                'response' => ['error_message' => $e->getMessage()],
            ]);
        }
    }
}
