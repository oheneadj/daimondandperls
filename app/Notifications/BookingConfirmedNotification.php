<?php

namespace App\Notifications;

use App\Models\Booking;
use App\Notifications\Channels\GaintSmsChannel;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class BookingConfirmedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(
        public Booking $booking,
        public ?string $invoiceUrl = null
    ) {
        //
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail', GaintSmsChannel::class];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $companyName = \App\Models\Setting::where('key', 'company_name')->value('value') ?? config('app.name');

        $mail = (new MailMessage)
            ->subject('Booking Confirmation - '.$this->booking->reference)
            ->greeting('Hello '.$notifiable->name.',')
            ->line('Your booking (Reference: '.$this->booking->reference.') has been successfully confirmed and payment is received.')
            ->line('Total Paid: GH₵'.number_format($this->booking->total_amount, 2));

        if ($this->invoiceUrl) {
            $mail->action('Download Invoice', $this->invoiceUrl)
                ->line('You can also view your booking details here: '.route('booking.confirmation', ['booking' => $this->booking->reference]));
        } else {
            $mail->action('View Booking', route('booking.confirmation', ['booking' => $this->booking->reference]));
        }

        $mail->line('Thank you for choosing '.$companyName.'!');

        return $mail;
    }

    /**
     * Get the SMS representation of the notification block.
     */
    public function toGaintSms(object $notifiable): string
    {
        $message = "Hi {$notifiable->name}, your booking {$this->booking->reference} for GHS "
            .number_format($this->booking->total_amount, 2)
            .' is confirmed! view: '
            .route('booking.confirmation', ['booking' => $this->booking->reference]);

        if ($this->invoiceUrl) {
            $message .= " Invoice: {$this->invoiceUrl}";
        }

        return $message;
    }

    /**
     * Optional method for custom channel to track the log.
     */
    public function getBookingId(): ?int
    {
        return $this->booking->id;
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
