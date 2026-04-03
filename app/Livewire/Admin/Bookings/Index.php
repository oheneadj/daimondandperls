<?php

declare(strict_types=1);

namespace App\Livewire\Admin\Bookings;

use App\Enums\BookingStatus;
use App\Enums\BookingType;
use App\Enums\PaymentStatus;
use App\Models\Booking;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.admin')]
#[Title('Meal Bookings')]
class Index extends Component
{
    use WithPagination;

    public string $search = '';

    public ?string $status = null;

    public ?string $paymentStatus = null;

    protected $queryString = [
        'search' => ['except' => ''],
        'status' => ['except' => ''],
        'paymentStatus' => ['except' => ''],
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingStatus()
    {
        $this->resetPage();
    }

    public function updatingPaymentStatus()
    {
        $this->resetPage();
    }

    public function confirmBooking(int $id): void
    {
        $booking = Booking::findOrFail($id);

        if ($booking->status === BookingStatus::Pending) {
            $booking->update([
                'status' => BookingStatus::Confirmed,
                'confirmed_at' => now(),
                'confirmed_by' => \Illuminate\Support\Facades\Auth::id(),
            ]);

            session()->flash('success', "Booking {$booking->reference} has been confirmed.");
        }
    }

    public function startPreparation(int $id): void
    {
        $booking = Booking::findOrFail($id);

        if ($booking->status === BookingStatus::Confirmed && $booking->payment_status === PaymentStatus::Paid) {
            $booking->update([
                'status' => BookingStatus::InPreparation,
            ]);

            session()->flash('info', "Booking {$booking->reference} implementation has started.");
        }
    }

    public function completeBooking(int $id): void
    {
        $booking = Booking::findOrFail($id);

        if ($booking->status === BookingStatus::InPreparation) {
            $booking->update([
                'status' => BookingStatus::Completed,
                'completed_at' => now(),
            ]);

            session()->flash('success', "Booking {$booking->reference} has been completed.");
        }
    }

    public function cancelBooking(int $id): void
    {
        $booking = Booking::findOrFail($id);

        if (! in_array($booking->status, [BookingStatus::Completed, BookingStatus::Cancelled])) {
            $booking->update([
                'status' => BookingStatus::Cancelled,
                'cancelled_at' => now(),
            ]);

            session()->flash('warning', "Booking {$booking->reference} has been cancelled.");
        }
    }

    public function verifyPayment(int $id): void
    {
        $booking = Booking::findOrFail($id);

        if ($booking->status === BookingStatus::Confirmed && $booking->payment_status !== PaymentStatus::Paid) {
            $booking->update([
                'payment_status' => PaymentStatus::Paid,
            ]);

            $booking->payment()->updateOrCreate(
                ['booking_id' => $booking->id],
                [
                    'amount' => $booking->total_amount,
                    'currency' => 'GHS',
                    'status' => \App\Enums\PaymentGatewayStatus::Successful,
                    'method' => \App\Enums\PaymentMethod::BankTransfer,
                    'gateway' => \App\Enums\PaymentGateway::Manual,
                    'paid_at' => now(),
                    'verified_by' => \Illuminate\Support\Facades\Auth::id(),
                    'verified_at' => now(),
                ]
            );

            session()->flash('success', "Payment for {$booking->reference} has been verified.");
        }
    }

    public function render()
    {
        $query = Booking::with(['customer', 'items.package'])
            ->where('booking_type', BookingType::Meal)
            ->when($this->search, function ($query) {
                $query->where('reference', 'like', '%'.$this->search.'%')
                    ->orWhereHas('customer', function ($q) {
                        $q->where('name', 'like', '%'.$this->search.'%')
                            ->orWhere('phone', 'like', '%'.$this->search.'%');
                    });
            })
            ->when($this->status, function ($query) {
                $query->where('status', $this->status);
            })
            ->when($this->paymentStatus, function ($query) {
                $query->where('payment_status', $this->paymentStatus);
            })
            ->latest();

        // Calculate counts separately for the stats bar (CursorPaginator doesn't support total/count)
        $counts = [
            'total' => (clone $query)->count(),
            'pending' => (clone $query)->where('status', \App\Enums\BookingStatus::Pending)->count(),
            'confirmed' => (clone $query)->where('status', \App\Enums\BookingStatus::Confirmed)->count(),
            'unpaid' => (clone $query)->where('payment_status', \App\Enums\PaymentStatus::Unpaid)->count(),
        ];

        $bookings = $query->simplePaginate(15);

        return view('livewire.admin.bookings.index', [
            'bookings' => $bookings,
            'counts' => $counts,
            'statuses' => \App\Enums\BookingStatus::cases(),
            'paymentStatuses' => \App\Enums\PaymentStatus::cases(),
        ]);
    }
}
