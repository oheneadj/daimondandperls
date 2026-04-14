<?php

namespace App\Notifications;

use App\Notifications\Channels\GaintSmsChannel;
use Illuminate\Notifications\Notification;

class OtpNotification extends Notification
{
    public function __construct(
        public string $otp,
        public string $purpose = 'login',
    ) {}

    public function via(object $notifiable): array
    {
        return [GaintSmsChannel::class];
    }

    public function toGaintSms(object $notifiable): string
    {
        $label = match ($this->purpose) {
            'payment_method' => 'verification',
            default => 'login',
        };

        return "Your Diamonds & Pearls {$label} code is: {$this->otp}. It expires in 10 minutes.";
    }
}
