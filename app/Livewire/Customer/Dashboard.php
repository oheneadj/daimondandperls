<?php

declare(strict_types=1);

namespace App\Livewire\Customer;

use App\Enums\BookingStatus;
use App\Enums\BookingType;
use App\Enums\PaymentStatus;
use App\Traits\ResolvesCustomer;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.customer')]
#[Title('Dashboard')]
class Dashboard extends Component
{
    use ResolvesCustomer;

    public function render(): View
    {
        $customer = $this->resolveCustomer();

        $totalBookings = 0;
        $upcomingBookings = 0;
        $totalSpent = 0;
        $pendingPayments = 0;
        $recentMeals = collect();
        $recentEvents = collect();

        if ($customer) {
            $totalBookings = $customer->bookings()->count();

            $upcomingBookings = $customer->bookings()
                ->whereIn('status', [BookingStatus::Pending, BookingStatus::Confirmed, BookingStatus::InPreparation])
                ->count();

            $totalSpent = (float) $customer->bookings()
                ->where('payment_status', PaymentStatus::Paid)
                ->sum('total_amount');

            $pendingPayments = $customer->bookings()
                ->where('payment_status', PaymentStatus::Unpaid)
                ->count();

            $recentMeals = $customer->bookings()
                ->where('booking_type', BookingType::Meal)
                ->with(['items.package', 'payment'])
                ->latest()
                ->take(3)
                ->get();

            $recentEvents = $customer->bookings()
                ->where('booking_type', BookingType::Event)
                ->with(['payment'])
                ->latest()
                ->take(3)
                ->get();
        }

        return view('livewire.customer.dashboard', [
            'totalBookings' => $totalBookings,
            'upcomingBookings' => $upcomingBookings,
            'totalSpent' => $totalSpent,
            'pendingPayments' => $pendingPayments,
            'recentMeals' => $recentMeals,
            'recentEvents' => $recentEvents,
        ]);
    }
}
