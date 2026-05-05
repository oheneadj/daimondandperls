<?php

declare(strict_types=1);

namespace App\Notifications;

use App\Models\Review;
use App\Notifications\Channels\SmsChannels;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;

class FriendReferralNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(public readonly Review $review) {}

    public function via(mixed $notifiable): array
    {
        return [SmsChannels::primary()];
    }

    public function toGaintSms(mixed $notifiable): string
    {
        $reviewerName = $this->review->author_name;
        $reviewerPhone = $this->review->reviewer_phone ?? $this->review->customer?->phone ?? '';
        $friendName = $this->review->friend_name;
        $shareUrl = route('review.share', $this->review->token);

        return "Hi {$friendName}! {$reviewerName} ({$reviewerPhone}) tried DPC Catering and loved it — they think you'd enjoy it too! Read their review and order: {$shareUrl}";
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
