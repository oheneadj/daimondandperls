<?php

namespace App\Notifications;

use App\Notifications\Channels\GaintSmsChannel;
use Illuminate\Notifications\Notification;

/**
 * Sends a one-time password (OTP) to the user via SMS.
 *
 * This notification is intentionally NOT queued so the user
 * receives the code immediately while waiting on the checkout page.
 */
class OtpNotification extends Notification
{
    /**
     * @param  string  $otp  The 6-digit OTP code to send.
     * @param  string  $purpose  The context for the OTP ('login' or 'payment_method').
     */
    public function __construct(
        public string $otp,
        public string $purpose = 'login',
    ) {}

    /**
     * Deliver via GaintSMS .
     */
    public function via(object $notifiable): array
    {
        return [GaintSmsChannel::class];
    }

    /**
     * Build the SMS message body.
     * The label changes based on the OTP purpose (login vs payment verification).
     */
    public function toGaintSms(object $notifiable): string
    {
        $label = match ($this->purpose) {
            'payment_method' => 'verification',
            default => 'login',
        };

        return "Your Diamonds & Pearls {$label} code is: {$this->otp}. It expires in 10 minutes.";
    }
}
