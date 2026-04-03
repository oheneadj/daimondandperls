<?php

declare(strict_types=1);

namespace App\Livewire\Admin\Bookings;

use App\Enums\BookingStatus;
use App\Enums\BookingType;
use App\Enums\EventType;
use App\Enums\PaymentStatus;
use App\Models\Booking;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.admin')]
#[Title('Event Bookings')]
class EventIndex extends Component
{
    use WithPagination;

    public string $search = '';

    public ?string $status = null;

    public ?string $paymentStatus = null;

    public ?string $eventType = null;

    public ?string $startDate = null;

    public ?string $endDate = null;

    protected $queryString = [
        'search' => ['except' => ''],
        'status' => ['except' => ''],
        'paymentStatus' => ['except' => ''],
        'eventType' => ['except' => ''],
        'startDate' => ['except' => ''],
        'endDate' => ['except' => ''],
    ];

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingStatus(): void
    {
        $this->resetPage();
    }

    public function updatingPaymentStatus(): void
    {
        $this->resetPage();
    }

    public function updatingEventType(): void
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
                'confirmed_by' => Auth::id(),
            ]);

            session()->flash('success', "Booking {$booking->reference} has been confirmed.");
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

    public function render()
    {
        $query = Booking::with(['customer', 'items.package'])
            ->where('booking_type', BookingType::Event)
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
            ->when($this->eventType, function ($query) {
                $query->where('event_type', $this->eventType);
            })
            ->when($this->startDate, function ($query) {
                $query->whereDate('event_date', '>=', $this->startDate);
            })
            ->when($this->endDate, function ($query) {
                $query->whereDate('event_date', '<=', $this->endDate);
            })
            ->latest();

        $counts = [
            'total' => (clone $query)->count(),
            'pending' => (clone $query)->where('status', BookingStatus::Pending)->count(),
            'confirmed' => (clone $query)->where('status', BookingStatus::Confirmed)->count(),
            'unpaid' => (clone $query)->where('payment_status', PaymentStatus::Unpaid)->count(),
        ];

        $bookings = $query->simplePaginate(15);

        return view('livewire.admin.bookings.event-index', [
            'bookings' => $bookings,
            'counts' => $counts,
            'statuses' => BookingStatus::cases(),
            'paymentStatuses' => PaymentStatus::cases(),
            'eventTypes' => EventType::cases(),
        ]);
    }
}
