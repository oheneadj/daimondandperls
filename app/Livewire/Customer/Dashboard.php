<?php

namespace App\Livewire\Customer;

use App\Models\Booking;
use App\Models\Customer;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('components.guest-layout')]
#[Title('My Bookings')]
class Dashboard extends Component
{
    public $bookings;

    public function mount()
    {
        $user = Auth::user();
        
        // Find customer associated with this user
        $customer = $user->customer;

        if (!$customer) {
            // Try to find customer by phone or email if not linked yet
            $customer = Customer::where(function($query) use ($user) {
                $query->where('phone', $user->phone)
                      ->orWhere('email', $user->email);
            })->first();

            if ($customer && !$customer->user_id) {
                $customer->update(['user_id' => $user->id]);
            }
        }

        if ($customer) {
            $this->bookings = $customer->bookings()
                ->with(['items.package', 'payment'])
                ->latest()
                ->get();
        } else {
            $this->bookings = collect();
        }
    }

    public function render()
    {
        return view('livewire.customer.dashboard');
    }
}
