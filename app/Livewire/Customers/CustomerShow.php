<?php

namespace App\Livewire\Customers;

use App\Enums\BookingStatus;
use App\Models\ActivityLog;
use App\Models\Customer;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

#[Title('Customer Details')]
#[Layout('layouts.admin')]
class CustomerShow extends Component
{
    use WithPagination;

    public Customer $customer;

    #[Url]
    public string $activeTab = 'bookings';

    // Bookings State
    #[Url]
    public string $searchBookings = '';

    #[Url]
    public string $filterBookingStatus = '';

    public string $sortBookingsField = 'created_at';

    public string $sortBookingsDirection = 'desc';

    // Payments State
    #[Url]
    public string $searchPayments = '';

    #[Url]
    public string $filterPaymentStatus = '';

    public string $sortPaymentsField = 'created_at';

    public string $sortPaymentsDirection = 'desc';

    // Activity State
    #[Url]
    public string $searchActivity = '';

    public function mount(Customer $customer): void
    {
        $this->customer = $customer;
    }

    public function updatedActiveTab(): void
    {
        $this->resetPage();
    }

    public function updatedSearchBookings(): void
    {
        $this->resetPage();
    }

    public function updatedSearchPayments(): void
    {
        $this->resetPage();
    }

    public function updatedSearchActivity(): void
    {
        $this->resetPage();
    }

    public function impersonate(): void
    {
        if (! $this->customer->user_id) {
            $this->addError('impersonate', 'This customer does not have a linked account.');

            return;
        }

        session(['impersonator_id' => Auth::id()]);
        Auth::login($this->customer->user);

        $this->redirectRoute('home');
    }

    public function setTab(string $tab): void
    {
        $this->activeTab = $tab;
    }

    public function sortByBookings(string $field): void
    {
        if ($this->sortBookingsField === $field) {
            $this->sortBookingsDirection = $this->sortBookingsDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortBookingsField = $field;
            $this->sortBookingsDirection = 'asc';
        }
    }

    public function sortByPayments(string $field): void
    {
        if ($this->sortPaymentsField === $field) {
            $this->sortPaymentsDirection = $this->sortPaymentsDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortPaymentsField = $field;
            $this->sortPaymentsDirection = 'asc';
        }
    }

    public function getLtvProperty(): float
    {
        return (float) $this->customer->bookings()
            ->where('status', BookingStatus::Confirmed->value)
            ->sum('total_amount');
    }

    public function getBookingsProperty()
    {
        return $this->customer->bookings()
            ->with(['items.package', 'payment'])
            ->when(fn () => filled($this->searchBookings), function ($query) {
                $query->where(function ($q) {
                    $q->where('reference', 'like', '%'.$this->searchBookings.'%')
                        ->orWhereHas('items.package', fn ($pq) => $pq->where('name', 'like', '%'.$this->searchBookings.'%'));
                });
            })
            ->when(fn () => filled($this->filterBookingStatus), function ($query) {
                $query->where('status', '=', $this->filterBookingStatus);
            })
            ->orderBy($this->sortBookingsField, $this->sortBookingsDirection)
            ->paginate(10, pageName: 'bookingsPage');
    }

    public function getPaymentsProperty()
    {
        return $this->customer->payments()
            ->when(fn () => filled($this->searchPayments), function ($query) {
                $query->where('gateway_reference', 'like', '%'.$this->searchPayments.'%');
            })
            ->when(fn () => filled($this->filterPaymentStatus), function ($query) {
                $query->where('status', '=', $this->filterPaymentStatus);
            })
            ->orderBy($this->sortPaymentsField, $this->sortPaymentsDirection)
            ->paginate(10, pageName: 'paymentsPage');
    }

    public function getActivitiesProperty()
    {
        if (! $this->customer->user_id) {
            return ActivityLog::where('user_id', '=', 0)->paginate(15);
        }

        return ActivityLog::where('user_id', '=', $this->customer->user_id)
            ->when(fn () => filled($this->searchActivity), function ($query) {
                $query->where(function ($q) {
                    $q->where('description', 'like', '%'.$this->searchActivity.'%')
                        ->orWhere('action', 'like', '%'.$this->searchActivity.'%');
                });
            })
            ->latest()
            ->paginate(15, pageName: 'activityPage');
    }

    public function render()
    {
        return view('livewire.customers.show', [
            'ltv' => $this->ltv,
            'bookings' => $this->bookings,
            'payments' => $this->payments,
            'activities' => $this->activities,
        ]);
    }
}
