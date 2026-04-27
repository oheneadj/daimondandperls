<?php

declare(strict_types=1);

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AdminInvitationNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public string $temporaryPassword,
        public string $inviteUrl,
    ) {}

    /** @return array<int, string> */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $companyName = dpc_setting('company_name') ?? config('app.name');

        return (new MailMessage)
            ->subject("You've been invited to {$companyName}")
            ->greeting("Hello {$notifiable->name},")
            ->line("You have been invited to join the **{$companyName}** admin team.")
            ->line('**Your login credentials:**')
            ->line("Email: `{$notifiable->email}`")
            ->line("Temporary Password: `{$this->temporaryPassword}`")
            ->action('Accept Invitation', $this->inviteUrl)
            ->line('This invitation link expires in 7 days.')
            ->line('For security, you will be required to change your password after your first login.');
    }
}
