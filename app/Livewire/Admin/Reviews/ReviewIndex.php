<?php

declare(strict_types=1);

namespace App\Livewire\Admin\Reviews;

use App\Models\Review;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.admin')]
#[Title('Reviews')]
class ReviewIndex extends Component
{
    use WithPagination;

    public ?int $filterStars = null;

    public ?bool $filterApproved = null;

    public ?Review $selectedReview = null;

    public bool $showViewModal = false;

    public function updatedFilterStars(): void
    {
        $this->resetPage();
    }

    public function updatedFilterApproved(): void
    {
        $this->resetPage();
    }

    public function viewReview(int $id): void
    {
        $this->selectedReview = Review::with(['booking', 'customer'])->findOrFail($id);
        $this->showViewModal = true;
    }

    public function approve(int $id): void
    {
        $review = Review::findOrFail($id);
        $review->update(['is_approved' => ! $review->is_approved]);
    }

    public function delete(int $id): void
    {
        Review::findOrFail($id)->delete();
        $this->dispatch('banner', ['style' => 'success', 'message' => 'Review deleted.']);
    }

    public function render(): mixed
    {
        $query = Review::query()
            ->with(['booking', 'customer'])
            ->whereNotNull('submitted_at')
            ->latest('submitted_at');

        if ($this->filterStars !== null) {
            $query->where('stars', $this->filterStars);
        }

        if ($this->filterApproved !== null) {
            $query->where('is_approved', $this->filterApproved);
        }

        return view('livewire.admin.reviews.review-index', [
            'reviews' => $query->paginate(15),
        ]);
    }
}
