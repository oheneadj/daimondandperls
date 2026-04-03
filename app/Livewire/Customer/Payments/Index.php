<?php

declare(strict_types=1);

namespace App\Livewire\Customer\Payments;

use App\Models\Payment;
use App\Traits\ResolvesCustomer;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.customer')]
#[Title('Payment History')]
class Index extends Component
{
    use ResolvesCustomer;
    use WithPagination;

    #[Url(except: '')]
    public string $search = '';

    #[Url(except: '')]
    public string $status = '';

    public ?int $showingPaymentId = null;

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function updatedStatus(): void
    {
        $this->resetPage();
    }

    public function viewPayment(int $id): void
    {
        $this->showingPaymentId = $id;
    }

    public function closeModal(): void
    {
        $this->showingPaymentId = null;
    }

    public function render(): View
    {
        $customer = $this->resolveCustomer();

        $payments = $customer
            ? $customer->payments()
                ->with(['booking'])
                ->when($this->status, fn ($q) => $q->where('payments.status', $this->status))
                ->when($this->search, fn ($q) => $q->whereHas('booking', fn ($bq) => $bq->where('reference', 'like', '%'.$this->search.'%')))
                ->latest('payments.created_at')
                ->simplePaginate(15)
            : collect();

        $selectedPayment = null;
        if ($this->showingPaymentId) {
            $selectedPayment = Payment::query()
                ->with(['booking.customer', 'booking.items.package'])
                ->whereHas('booking', fn ($q) => $q->where('customer_id', $customer?->id))
                ->find($this->showingPaymentId);
        }

        return view('livewire.customer.payments.index', [
            'payments' => $payments,
            'selectedPayment' => $selectedPayment,
        ]);
    }
}
