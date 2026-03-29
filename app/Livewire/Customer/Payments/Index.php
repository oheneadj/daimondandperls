<?php

declare(strict_types=1);

namespace App\Livewire\Customer\Payments;

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
    public string $status = '';

    public function updatedStatus(): void
    {
        $this->resetPage();
    }

    public function render(): View
    {
        $customer = $this->resolveCustomer();

        $payments = $customer
            ? $customer->payments()
                ->with(['booking'])
                ->when($this->status, fn ($q) => $q->where('payments.status', $this->status))
                ->latest('payments.created_at')
                ->simplePaginate(15)
            : collect();

        return view('livewire.customer.payments.index', [
            'payments' => $payments,
        ]);
    }
}
