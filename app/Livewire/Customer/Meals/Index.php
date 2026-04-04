<?php

declare(strict_types=1);

namespace App\Livewire\Customer\Meals;

use App\Enums\BookingType;
use App\Traits\ResolvesCustomer;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.customer')]
#[Title('My Meal Orders')]
class Index extends Component
{
    use ResolvesCustomer;
    use WithPagination;

    #[Url(except: '')]
    public string $search = '';

    #[Url(except: '')]
    public string $status = '';

    #[Url(except: '')]
    public string $paymentStatus = '';

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function updatedStatus(): void
    {
        $this->resetPage();
    }

    public function updatedPaymentStatus(): void
    {
        $this->resetPage();
    }

    public function clearFilters(): void
    {
        $this->reset(['search', 'status', 'paymentStatus']);
        $this->resetPage();
    }

    public function render(): View
    {
        $customer = $this->resolveCustomer();

        $bookings = $customer
            ? $customer->bookings()
                ->where('booking_type', BookingType::Meal)
                ->with(['items.package', 'payment'])
                ->when($this->search, fn ($q) => $q->where('reference', 'like', "%{$this->search}%"))
                ->when($this->status, fn ($q) => $q->where('status', $this->status))
                ->when($this->paymentStatus, fn ($q) => $q->where('payment_status', $this->paymentStatus))
                ->latest()
                ->simplePaginate(10)
            : collect();

        return view('livewire.customer.meals.index', [
            'bookings' => $bookings,
        ]);
    }
}
