<?php

declare(strict_types=1);

namespace App\Notifications;

use App\Models\Booking;
use App\Notifications\Channels\SmsChannels;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class BookingReceivedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(public Booking $booking) {}

    public function via(object $notifiable): array
    {
        $channels = ['database'];

        $preference = $notifiable->notification_preference ?? \App\Enums\NotificationPreference::Email;

        if ($preference === \App\Enums\NotificationPreference::Email || $preference === \App\Enums\NotificationPreference::Both) {
            $channels[] = 'mail';
        }

        if ($preference === \App\Enums\NotificationPreference::Sms || $preference === \App\Enums\NotificationPreference::Both) {
            $channels[] = SmsChannels::primary();
        }

        return $channels;
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('New Booking Received: '.$this->booking->reference)
            ->greeting('Hello '.$notifiable->name.'!')
            ->line('A new booking has been received.')
            ->line('Booking Reference: '.$this->booking->reference)
            ->line('Customer: '.$this->booking->customer->name)
            ->line('Total Amount: GHS '.number_format((float) $this->booking->total_amount, 2))
            ->action('View Booking', route('admin.bookings.show', $this->booking->reference))
            ->line('Thank you for using our application!');
    }

    public function toSms(object $notifiable): string
    {
        return "New Booking Received!\nRef: {$this->booking->reference}\nAmt: GHS ".number_format((float) $this->booking->total_amount, 2)."\nView: ".route('admin.bookings.show', $this->booking->reference);
    }

    public function toGaintSms(object $notifiable): string
    {
        return $this->toSms($notifiable);
    }

    public function getBookingId(): int
    {
        return $this->booking->id;
    }

    public function toArray(object $notifiable): array
    {
        $customer = $this->booking->customer?->name ?? 'Guest';

        return [
            'type' => 'booking_received',
            'booking_id' => $this->booking->id,
            'reference' => $this->booking->reference,
            'customer_name' => $customer,
            'amount' => $this->booking->total_amount,
            'message' => "New booking from {$customer}: {$this->booking->reference}",
            'action_url' => route('admin.bookings.show', $this->booking->reference),
        ];
    }
}
