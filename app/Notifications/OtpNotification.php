<?php

namespace App\Notifications;

use App\Notifications\Channels\GaintSmsChannel;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;

class OtpNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(public string $otp)
    {
    }

    public function via(object $notifiable): array
    {
        return [GaintSmsChannel::class];
    }

    public function toGaintSms(object $notifiable): string
    {
        return "Your Diamonds & Pearls login code is: {$this->otp}. It expires in 10 minutes.";
    }
}
