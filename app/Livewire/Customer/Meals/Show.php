<?php

declare(strict_types=1);

namespace App\Livewire\Customer\Meals;

use App\Enums\BookingType;
use App\Models\Booking;
use App\Traits\ResolvesCustomer;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.customer')]
#[Title('Meal Order Details')]
class Show extends Component
{
    use ResolvesCustomer;

    public Booking $booking;

    public function mount(Booking $booking): void
    {
        $customer = $this->resolveCustomer();

        abort_unless($customer && $booking->customer_id === $customer->id, 403);
        abort_unless($booking->booking_type === BookingType::Meal, 404);

        $this->booking = $booking->load(['items.package', 'payment', 'customer']);
    }

    public function render(): View
    {
        return view('livewire.customer.meals.show');
    }
}
