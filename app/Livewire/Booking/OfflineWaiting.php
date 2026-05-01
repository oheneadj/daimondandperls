<?php

declare(strict_types=1);

namespace App\Livewire\Booking;

use App\Enums\PaymentStatus;
use App\Models\Booking;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('components.guest-layout')]
#[Title('Awaiting Payment Confirmation')]
class OfflineWaiting extends Component
{
    public Booking $booking;

    public function mount(Booking $booking): void
    {
        $this->booking = $booking->load('customer');
    }

    public function checkConfirmation(): void
    {
        $this->booking->refresh();

        if ($this->booking->payment_status === PaymentStatus::Paid) {
            $this->redirect(route('booking.confirmation', ['booking' => $this->booking->reference]));
        }
    }

    public function render(): View
    {
        return view('livewire.booking.offline-waiting');
    }
}
