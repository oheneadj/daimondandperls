<?php

declare(strict_types=1);

namespace App\Notifications;

use App\Notifications\Channels\SmsChannels;
use Illuminate\Notifications\Notification;

/**
 * Sends a one-time password (OTP) to the user via SMS.
 *
 * Initial sends use the primary SMS provider; resends use the secondary
 * provider so a different route is tried if the first message did not arrive.
 *
 * This notification is intentionally NOT queued so the user receives the
 * code immediately while waiting on the checkout or verification page.
 */
class OtpNotification extends Notification
{
    /**
     * @param  string  $otp  The 6-digit OTP code to send.
     * @param  string  $purpose  Context: 'login' or 'payment_method'.
     * @param  bool  $isResend  Whether this is a resend (uses secondary provider).
     */
    public function __construct(
        public readonly string $otp,
        public readonly string $purpose = 'login',
        public readonly bool $isResend = false,
    ) {}

    public function via(object $notifiable): array
    {
        return SmsChannels::forOtp($this->isResend);
    }

    public function toSms(object $notifiable): string
    {
        $label = match ($this->purpose) {
            'payment_method' => 'verification',
            default => 'login',
        };

        return "Your Diamonds & Pearls {$label} code is: {$this->otp}. It expires in 10 minutes.";
    }

    /** Backwards-compat alias used by GaintSmsChannel when toSms is not detected first. */
    public function toGaintSms(object $notifiable): string
    {
        return $this->toSms($notifiable);
    }
}
