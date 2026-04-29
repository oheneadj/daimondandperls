<?php

namespace App\Notifications;

use App\Models\Booking;
use App\Notifications\Channels\SmsChannels;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class BookingCompletedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(public Booking $booking)
    {
        //
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail', 'database', SmsChannels::primary()];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Your Event Catering is Complete! - '.$this->booking->reference)
            ->greeting('Hello '.$notifiable->name.',')
            ->line('We are delighted to inform you that your catering service for booking '.$this->booking->reference.' has been successfully completed.')
            ->line('We hope you and your guests enjoyed the selection from Diamonds & Pearls.')
            ->action('View Booking Details', route('booking.confirmation', ['booking' => $this->booking->reference]))
            ->line('Thank you for choosing Diamonds & Pearls. We look forward to serving you again soon!');
    }

    /**
     * Get the SMS representation of the notification.
     */
    public function toSms(object $notifiable): string
    {
        return "Hi {$notifiable->name}, your Diamonds & Pearls booking {$this->booking->reference} is now complete. We hope you enjoyed our service! Details: "
            .route('booking.confirmation', ['booking' => $this->booking->reference]);
    }

    public function toGaintSms(object $notifiable): string
    {
        return $this->toSms($notifiable);
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'booking_completed',
            'booking_id' => $this->booking->id,
            'reference' => $this->booking->reference,
            'message' => "Booking {$this->booking->reference} has been completed.",
            'action_url' => route('booking.confirmation', ['booking' => $this->booking->reference]),
        ];
    }

    /**
     * Optional method for custom channel to track the log.
     */
    public function getBookingId(): ?int
    {
        return $this->booking->id;
    }
}
