<?php

declare(strict_types=1);

namespace App\Notifications;

use App\Enums\NotificationPreference;
use App\Models\ContactMessage;
use App\Notifications\Channels\MailChannels;
use App\Notifications\Channels\SmsChannels;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ContactMessageReceivedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(public ContactMessage $contactMessage) {}

    public function via(object $notifiable): array
    {
        $channels = ['database'];

        $preference = $notifiable->notification_preference ?? NotificationPreference::Email;

        if ($preference === NotificationPreference::Email || $preference === NotificationPreference::Both) {
            $channels[] = 'mail';
        }

        if ($preference === NotificationPreference::Sms || $preference === NotificationPreference::Both) {
            $channels[] = SmsChannels::primary();
        }

        return $channels;
    }

    public function toMail(object $notifiable): MailMessage
    {
        $snippet = mb_substr($this->contactMessage->message, 0, 100).(strlen($this->contactMessage->message) > 100 ? '...' : '');

        return (new MailMessage)
            ->mailer(MailChannels::primary())
            ->subject('New Contact Message: '.$this->contactMessage->inquiry_type)
            ->greeting('Hello '.$notifiable->name.'!')
            ->line('A new contact message has been received.')
            ->line('From: '.$this->contactMessage->name)
            ->line('Inquiry: '.$this->contactMessage->inquiry_type)
            ->line('Message: '.$snippet)
            ->action('View Message', route('admin.contact-messages'))
            ->line('Log in to the admin panel to read the full message and respond.');
    }

    public function toSms(object $notifiable): string
    {
        return "New Contact: {$this->contactMessage->name} ({$this->contactMessage->inquiry_type}). View: ".route('admin.contact-messages');
    }

    public function toGaintSms(object $notifiable): string
    {
        return $this->toSms($notifiable);
    }

    public function toArray(object $notifiable): array
    {
        $snippet = mb_substr($this->contactMessage->message, 0, 80).(strlen($this->contactMessage->message) > 80 ? '...' : '');

        return [
            'type' => 'contact_message_received',
            'contact_message_id' => $this->contactMessage->id,
            'name' => $this->contactMessage->name,
            'inquiry_type' => $this->contactMessage->inquiry_type,
            'message' => "New contact from {$this->contactMessage->name}: {$snippet}",
            'action_url' => route('admin.contact-messages'),
        ];
    }
}
