<?php

declare(strict_types=1);

namespace App\Notifications;

use App\Models\Booking;
use App\Notifications\Channels\MailChannels;
use App\Notifications\Channels\SmsChannels;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class QuoteUpdatedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public Booking $booking
    ) {}

    /**
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail', 'database', SmsChannels::primary()];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $companyName = dpc_setting('company_name') ?? config('app.name');

        return (new MailMessage)
            ->mailer(MailChannels::primary())
            ->subject('Your Event Quote is Ready - '.$this->booking->reference)
            ->greeting('Hello '.$notifiable->name.',')
            ->line('Great news! We have prepared a quote for your event booking (Reference: '.$this->booking->reference.').')
            ->line('Quoted Amount: GH₵'.number_format((float) $this->booking->total_amount, 2))
            ->action('View & Pay', route('booking.payment', ['booking' => $this->booking->reference]))
            ->line('Thank you for choosing '.$companyName.'!');
    }

    public function toSms(object $notifiable): string
    {
        return "Hi {$notifiable->name}, your quote for {$this->booking->reference} is GH₵"
            .number_format((float) $this->booking->total_amount, 2)
            .'. Pay here: '
            .route('booking.payment', ['booking' => $this->booking->reference]);
    }

    public function toGaintSms(object $notifiable): string
    {
        return $this->toSms($notifiable);
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'quote_ready',
            'booking_id' => $this->booking->id,
            'reference' => $this->booking->reference,
            'amount' => $this->booking->total_amount,
            'message' => "Quote ready for {$this->booking->reference}: GH₵".number_format((float) $this->booking->total_amount, 2),
            'action_url' => route('booking.payment', ['booking' => $this->booking->reference]),
        ];
    }

    public function getBookingId(): ?int
    {
        return $this->booking->id;
    }
}
