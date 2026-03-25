<?php

namespace App\Livewire\Booking;

use App\Models\Booking;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('components.guest-layout')]
class TrackBooking extends Component
{
    public string $reference = '';
    public string $phone = '';
    public ?string $message = null;

    public function track()
    {
        $this->validate([
            'reference' => 'required|string',
            'phone' => ['required', 'regex:/^(?:\+233|0)\d{9}$/'],
        ], [
            'phone.regex' => 'Please enter a valid Ghanaian phone number.',
        ]);

        $booking = Booking::where('reference', $this->reference)
            ->whereHas('customer', function ($query) {
                $query->where('phone', $this->phone);
            })
            ->first();

        if (!$booking) {
            $this->message = 'We couldn\'t find a booking matching those details. Please check and try again.';
            return;
        }

        if ($booking->payment_status === \App\Enums\PaymentStatus::Paid) {
            return redirect()->route('booking.confirmation', ['booking' => $booking->reference]);
        }

        return redirect()->route('booking.payment', ['booking' => $booking->reference]);
    }

    #[Title('Track My Booking')]
    public function render()
    {
        return view('livewire.booking.track-booking');
    }
}
