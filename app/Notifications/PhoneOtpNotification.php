<?php

declare(strict_types=1);

namespace App\Notifications;

use App\Notifications\Channels\SmsChannels;
use Illuminate\Notifications\Notification;

class PhoneOtpNotification extends Notification
{
    public function __construct(
        public readonly string $otp,
        public readonly bool $isResend = false,
    ) {}

    public function via(object $notifiable): array
    {
        return SmsChannels::forOtp($this->isResend);
    }

    public function toSms(object $notifiable): string
    {
        return "Your Diamonds & Pearls verification code is: {$this->otp}. It expires in 10 minutes.";
    }

    public function toGaintSms(object $notifiable): string
    {
        return $this->toSms($notifiable);
    }
}
