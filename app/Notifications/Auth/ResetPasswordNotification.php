<?php

declare(strict_types=1);

namespace App\Notifications\Auth;

use App\Notifications\Channels\MailChannels;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class ResetPasswordNotification extends ResetPassword implements ShouldQueue
{
    use Queueable;

    public int $tries = 3;

    public function toMail(mixed $notifiable): MailMessage
    {
        // I call parent to get the standard reset password mail, then override the mailer.
        $mail = parent::toMail($notifiable);

        return $mail->mailer(MailChannels::primary());
    }
}
