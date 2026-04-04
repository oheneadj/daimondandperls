<?php

declare(strict_types=1);

namespace App\Livewire\Customer\Events;

use App\Enums\BookingType;
use App\Traits\ResolvesCustomer;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.customer')]
#[Title('My Events')]
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

    #[Url(except: '')]
    public string $eventType = '';

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

    public function updatedEventType(): void
    {
        $this->resetPage();
    }

    public function clearFilters(): void
    {
        $this->reset(['search', 'status', 'paymentStatus', 'eventType']);
        $this->resetPage();
    }

    public function render(): View
    {
        $customer = $this->resolveCustomer();

        $bookings = $customer
            ? $customer->bookings()
                ->where('booking_type', BookingType::Event)
                ->with(['payment'])
                ->when($this->search, fn ($q) => $q->where('reference', 'like', "%{$this->search}%"))
                ->when($this->status, fn ($q) => $q->where('status', $this->status))
                ->when($this->paymentStatus, fn ($q) => $q->where('payment_status', $this->paymentStatus))
                ->when($this->eventType, fn ($q) => $q->where('event_type', $this->eventType))
                ->latest()
                ->simplePaginate(10)
            : collect();

        return view('livewire.customer.events.index', [
            'bookings' => $bookings,
        ]);
    }
}
