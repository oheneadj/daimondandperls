<?php

declare(strict_types=1);

namespace App\Livewire\Review;

use App\Models\Review;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('components.guest-layout')]
#[Title('A friend thinks you\'d love this')]
class ReviewShare extends Component
{
    public Review $review;

    public string $orderUrl = '';

    public function mount(string $token): void
    {
        $review = Review::findByToken($token);

        if (! $review || ! $review->submitted_at) {
            abort(404);
        }

        $this->review = $review;

        $referralCode = $review->customer?->referral_code;
        $this->orderUrl = $referralCode
            ? route('packages.browse').'?ref='.$referralCode
            : route('packages.browse');
    }

    public function render(): mixed
    {
        return view('livewire.review.review-share');
    }
}
