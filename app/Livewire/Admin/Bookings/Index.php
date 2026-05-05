<?php

declare(strict_types=1);

namespace App\Livewire\Admin\Bookings;

use App\Enums\BookingStatus;
use App\Enums\BookingType;
use App\Enums\PaymentGateway;
use App\Enums\PaymentGatewayStatus;
use App\Enums\PaymentMethod;
use App\Enums\PaymentStatus;
use App\Models\Booking;
use App\Traits\HasAdminAuthorization;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.admin')]
#[Title('Meal Bookings')]
class Index extends Component
{
    use HasAdminAuthorization;
    use WithPagination;

    public string $search = '';

    public ?string $status = null;

    public ?string $paymentStatus = null;

    #[Url]
    public string $startDate = '';

    #[Url]
    public string $endDate = '';

    protected $queryString = [
        'search' => ['except' => ''],
        'status' => ['except' => ''],
        'paymentStatus' => ['except' => ''],
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

    public function updatingStartDate(): void
    {
        $this->resetPage();
    }

    public function updatingEndDate(): void
    {
        $this->resetPage();
    }

    public function filterToday(): void
    {
        $this->startDate = now()->toDateString();
        $this->endDate = now()->toDateString();
        $this->resetPage();
    }

    public function filterThisWeek(): void
    {
        $this->startDate = now()->startOfWeek()->toDateString();
        $this->endDate = now()->endOfWeek()->toDateString();
        $this->resetPage();
    }

    public function clearDateFilter(): void
    {
        $this->startDate = '';
        $this->endDate = '';
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
                    'status' => PaymentGatewayStatus::Successful,
                    'method' => PaymentMethod::BankTransfer,
                    'gateway' => PaymentGateway::Manual,
                    'paid_at' => now(),
                    'verified_by' => Auth::id(),
                    'verified_at' => now(),
                ]
            );

            session()->flash('success', "Payment for {$booking->reference} has been verified.");
        }
    }

    public function mount(): void
    {
        $this->authorizePermission('manage_bookings');
    }

    public function render()
    {
        $baseQuery = Booking::query()->where('booking_type', BookingType::Meal);

        $query = (clone $baseQuery)
            ->with(['customer', 'items.package'])
            ->withMin('items', 'scheduled_date')
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('reference', 'like', '%'.$this->search.'%')
                        ->orWhereHas('customer', function ($cq) {
                            $cq->where('name', 'like', '%'.$this->search.'%')
                                ->orWhere('phone', 'like', '%'.$this->search.'%');
                        });
                });
            })
            ->when($this->status, fn ($q) => $q->where('status', $this->status))
            ->when($this->paymentStatus, fn ($q) => $q->where('payment_status', $this->paymentStatus))
            ->when($this->startDate, fn ($q) => $q->whereHas('items', fn ($sq) => $sq->whereDate('scheduled_date', '>=', $this->startDate)
            ))
            ->when($this->endDate, fn ($q) => $q->whereHas('items', fn ($sq) => $sq->whereDate('scheduled_date', '<=', $this->endDate)
            ))
            ->latest();

        $counts = [
            'total' => (clone $baseQuery)->count(),
            'pending' => (clone $baseQuery)->where('status', BookingStatus::Pending)->count(),
            'confirmed' => (clone $baseQuery)->where('status', BookingStatus::Confirmed)->count(),
            'unpaid' => (clone $baseQuery)->where('payment_status', PaymentStatus::Unpaid)->count(),
            'today' => (clone $baseQuery)->whereHas('items', fn ($q) => $q->whereDate('scheduled_date', today())
            )->count(),
        ];

        $bookings = $query->simplePaginate(15);

        return view('livewire.admin.bookings.index', [
            'bookings' => $bookings,
            'counts' => $counts,
            'statuses' => BookingStatus::cases(),
            'paymentStatuses' => PaymentStatus::cases(),
        ]);
    }
}
