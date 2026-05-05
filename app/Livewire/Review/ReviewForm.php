<?php

declare(strict_types=1);

namespace App\Livewire\Review;

use App\Models\Review;
use App\Notifications\FriendReferralNotification;
use App\Services\LoyaltyService;
use Illuminate\Support\Facades\Notification;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('components.guest-layout')]
#[Title('Rate Your Experience')]
class ReviewForm extends Component
{
    public string $token = '';

    public ?Review $review = null;

    public int $stars = 0;

    public string $authorName = '';

    public string $reviewerPhone = '';

    public string $message = '';

    public bool $submitted = false;

    public string $friendName = '';

    public string $friendPhone = '';

    public bool $friendNominated = false;

    public ?string $friendError = null;

    public function mount(string $token): void
    {
        $this->review = Review::findByToken($token) ?? abort(404);
        $this->authorName = $this->review->customer?->name ?? '';
        $this->reviewerPhone = $this->review->customer?->phone ?? '';

        if ($this->review->submitted_at !== null) {
            $this->submitted = true;
            $this->stars = $this->review->stars ?? 0;
            $this->authorName = $this->review->author_name ?? '';
            $this->message = $this->review->message ?? '';
            $this->friendNominated = $this->review->friend_sms_sent_at !== null;
        }
    }

    public function submit(): void
    {
        if ($this->review->submitted_at !== null) {
            return;
        }

        $this->validate([
            'stars' => 'required|integer|min:1|max:5',
            'authorName' => 'required|string|max:100',
            'reviewerPhone' => ['nullable', 'regex:/^(?:\+233|0)\d{9}$/'],
            'message' => 'nullable|string|max:1000',
        ]);

        $this->review->update([
            'stars' => $this->stars,
            'author_name' => $this->authorName,
            'reviewer_phone' => $this->reviewerPhone ?: null,
            'message' => $this->message ?: null,
            'is_approved' => true,
            'submitted_at' => now(),
        ]);

        app(LoyaltyService::class)->creditForReview($this->review->fresh());

        $this->submitted = true;
    }

    public function nominateFriend(): void
    {
        $this->friendError = null;

        if ($this->review->friend_sms_sent_at !== null) {
            $this->friendError = 'You have already nominated a friend for this review.';

            return;
        }

        $this->validate([
            'friendName' => 'required|string|max:100',
            'friendPhone' => ['required', 'regex:/^(?:\+233|0)\d{9}$/'],
        ]);

        $normalised = preg_replace('/\s+/', '', $this->friendPhone);

        $alreadyReferred = Review::whereNotNull('friend_sms_sent_at')
            ->where('friend_phone', $normalised)
            ->exists();

        if ($alreadyReferred) {
            $this->friendError = 'This number has already been referred to us by another customer.';

            return;
        }

        $this->review->update([
            'friend_name' => $this->friendName,
            'friend_phone' => $normalised,
            'friend_sms_sent_at' => now(),
        ]);

        Notification::route('gaintsms', $normalised)
            ->notify(new FriendReferralNotification($this->review->fresh()));

        $this->friendNominated = true;
    }

    public function render(): mixed
    {
        return view('livewire.review.review-form');
    }
}
