<?php

declare(strict_types=1);

namespace App\Notifications\Channels;

use App\Models\SmsLog;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class MNotifyChannel
{
    public function send(object $notifiable, Notification $notification): void
    {
        if (! method_exists($notification, 'toSms')) {
            return;
        }

        $message = $notification->toSms($notifiable);

        $phoneNumber = $notifiable->routeNotificationFor('mnotify')
            ?? $notifiable->phone
            ?? null;

        if (! $phoneNumber) {
            Log::warning('MNotifyChannel: No phone number found for notifiable.');

            return;
        }

        $cleanedPhone = preg_replace('/[^0-9\+]/', '', $phoneNumber);

        $senderId = config('services.mnotify.sender_id');
        $apiKey = config('services.mnotify.api_key');

        if (! $senderId || ! $apiKey) {
            Log::error('MNotifyChannel: Missing mNotify credentials.');

            return;
        }

        $bookingId = method_exists($notification, 'getBookingId') ? $notification->getBookingId() : null;

        try {
            $response = Http::asJson()->post("https://api.mnotify.com/api/sms/quick?key={$apiKey}", [
                'recipient' => [$cleanedPhone],
                'sender' => $senderId,
                'message' => $message,
                'is_schedule' => false,
                'schedule_date' => '',
            ]);

            $status = 'failed';
            $messageId = null;

            if ($response->successful()) {
                $data = $response->json();
                if (($data['code'] ?? null) === '2000') {
                    $status = 'sent';
                    $messageId = $data['summary']['_id'] ?? null;
                } else {
                    Log::error('MNotifyChannel: API returned error.', ['response' => $response->body()]);
                }
            } else {
                Log::error('MNotifyChannel: HTTP request failed.', ['status' => $response->status(), 'response' => $response->body()]);
            }

            SmsLog::create([
                'booking_id' => $bookingId,
                'message_id' => $messageId,
                'to' => $cleanedPhone,
                'message' => $message,
                'status' => $status,
                'response' => $response->json(),
                'provider' => 'mnotify',
            ]);

        } catch (\Exception $e) {
            Log::error('MNotifyChannel: Exception thrown.', ['exception' => $e->getMessage()]);

            SmsLog::create([
                'booking_id' => $bookingId,
                'to' => $cleanedPhone,
                'message' => $message,
                'status' => 'failed',
                'response' => ['error_message' => $e->getMessage()],
                'provider' => 'mnotify',
            ]);
        }
    }
}
