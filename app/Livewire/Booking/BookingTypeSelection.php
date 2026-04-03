<?php

declare(strict_types=1);

namespace App\Livewire\Booking;

use App\Services\CartService;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('How would you like to book?')]
#[Layout('components.guest-layout')]
class BookingTypeSelection extends Component
{
    public function mount(): void
    {
        if (app(CartService::class)->count() === 0) {
            $this->redirect(route('home'));
        }
    }

    public function selectMeal(): void
    {
        $this->redirect(route('checkout'));
    }

    public function selectEvent(): void
    {
        $this->redirect(route('event-booking'));
    }

    public function render(CartService $cart): View
    {
        if ($cart->count() === 0) {
            $this->redirect(route('home'));
        }

        return view('livewire.booking.booking-type-selection', [
            'cartCount' => $cart->count(),
            'cartItems' => $cart->getCart(),
        ]);
    }
}
