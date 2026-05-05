<?php

declare(strict_types=1);

namespace App\Notifications;

use App\Models\Review;
use App\Notifications\Channels\SmsChannels;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;

class ReviewRequestNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(public readonly Review $review) {}

    public function via(mixed $notifiable): array
    {
        return [SmsChannels::primary()];
    }

    public function toGaintSms(mixed $notifiable): string
    {
        $points = (int) dpc_setting('review_points_reward', 25);
        $name = $notifiable->name ?? 'there';
        $ref = $this->review->booking->reference;
        $url = route('review.form', $this->review->token);

        return "Hi {$name}! How was your order #{$ref}? Rate your experience and earn {$points} loyalty points: {$url}";
    }

    public function toSms(mixed $notifiable): string
    {
        return $this->toGaintSms($notifiable);
    }

    public function getBookingId(): ?int
    {
        return $this->review->booking_id;
    }
}
