<?php

declare(strict_types=1);

namespace App\Notifications;

use App\Models\Booking;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class CustomerBookingReceivedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(public Booking $booking) {}

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $resumeUrl = route('booking.payment', $this->booking->reference);

        return (new MailMessage)
            ->subject('Booking Received: '.$this->booking->reference)
            ->greeting('Hello '.$this->booking->customer->name.'!')
            ->line('Thank you for choosing Diamonds & Pearls Catering. Your booking has been received and is currently pending payment.')
            ->line('Booking Reference: **'.$this->booking->reference.'**')
            ->line('Total Amount: **GHS '.number_format((float) $this->booking->total_amount, 2).'**')
            ->action('Complete Payment', $resumeUrl)
            ->line('If you have already initiated a bank transfer, please ignore this message while we verify your payment.')
            ->line('Thank you for your business!');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'booking_id' => $this->booking->id,
            'reference' => $this->booking->reference,
            'amount' => $this->booking->total_amount,
            'status' => 'pending_payment',
        ];
    }
}
