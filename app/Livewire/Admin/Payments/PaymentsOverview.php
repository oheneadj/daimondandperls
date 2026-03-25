<?php

declare(strict_types=1);

namespace App\Livewire\Admin\Payments;

use App\Enums\BookingStatus;
use App\Enums\PaymentGateway;
use App\Enums\PaymentGatewayStatus;
use App\Models\Payment;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.admin')]
#[Title('Payments Overview')]
class PaymentsOverview extends Component
{
    use WithPagination;
    #[Url(history: true)]
    public string $search = '';
    #[Url(history: true)]
    public string $activeTab = 'all';
    public string $sortField = 'created_at';
    public string $sortDirection = 'desc';
    public function sortBy(string $field): void
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }
        $this->resetPage();
    }
    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    // Verification State
    public bool $showingVerifyModal = false;
    public ?Payment $paymentToVerify = null;

    // Show Modal State
    public bool $showingPaymentModal = false;
    public ?Payment $selectedPayment = null;

    public ?string $loading = null;

    public function showPayment(int $id): void
    {
        $this->selectedPayment = Payment::with(['booking.customer', 'verifiedBy'])->findOrFail($id);
        $this->showingPaymentModal = true;
    }

    public function closePaymentModal(): void
    {
        $this->showingPaymentModal = false;
        $this->selectedPayment = null;
    }

    public function updatedActiveTab(): void
    {
        $this->resetPage();
    }

    public function confirmVerify(int $id): void
    {
        $this->loading = 'confirmVerify-'.$id;
        $payment = Payment::findOrFail($id);

        if ($payment->status === PaymentGatewayStatus::Pending && $payment->gateway === \App\Enums\PaymentGateway::Manual) {
            $this->paymentToVerify = $payment;
            $this->showingVerifyModal = true;
        } else {
            $this->dispatch('notify',
                message: 'This payment cannot be manually verified.',
                type: 'error'
            );
        }
        $this->loading = null;
    }

    public function verifyPayment(): void
    {
        if ($this->paymentToVerify) {
            $this->paymentToVerify->update([
                'status' => PaymentGatewayStatus::Successful,
                'verified_by' => \Illuminate\Support\Facades\Auth::id(),
                'verified_at' => now(),
                'paid_at' => now(),
            ]);

            // Also update the booking status if it's currently pending
            if ($this->paymentToVerify->booking->status === BookingStatus::Pending) {
                $this->paymentToVerify->booking->update([
                    'status' => BookingStatus::Confirmed,
                    'payment_status' => \App\Enums\PaymentStatus::Paid,
                    'confirmed_at' => now(),
                    'confirmed_by' => \Illuminate\Support\Facades\Auth::id(),
                ]);

                // Generate and send invoice
                $booking = $this->paymentToVerify->booking;
                $invoiceUrl = app(\App\Services\InvoiceService::class)->getDownloadUrl($booking);
                
                if ($booking->customer) {
                    $booking->customer->notify(new \App\Notifications\BookingConfirmedNotification($booking, $invoiceUrl));
                }
            }

            session()->flash('success', "Payment for booking #{$this->paymentToVerify->booking->reference} verified successfully.");
        }

        $this->showingVerifyModal = false;
        $this->paymentToVerify = null;
    }

    public function render(): View
    {
        $stats = [
            'total_received' => Payment::where('status', PaymentGatewayStatus::Successful)->sum('amount'),
            'pending_verification' => Payment::where('status', PaymentGatewayStatus::Pending)->where('gateway', PaymentGateway::Manual)->count(),
            'failed_transactions' => Payment::where('status', PaymentGatewayStatus::Failed)->count(),
        ];

        $query = Payment::query()
            ->with(['booking.customer', 'verifiedBy']);

        if (filled($this->search)) {
            $query->whereHas('booking', function ($q) {
                $search = $this->search;
                $q->where('reference', 'like', "%{$search}%")
                    ->orWhereHas('customer', function ($cq) use ($search) {
                        $cq->where('name', 'like', "%{$search}%");
                    });
            });
        }

        if ($this->activeTab !== 'all') {
            $status = match ($this->activeTab) {
                'paid' => PaymentGatewayStatus::Successful,
                'pending' => PaymentGatewayStatus::Pending,
                'failed' => PaymentGatewayStatus::Failed,
                default => null
            };
            if ($status) {
                $query->where(fn($q) => $q->where('status', '=', $status->value));
            }
        }

        $payments = $query
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate(15);

        return view('livewire.admin.payments.overview', [
            'payments' => $payments,
            'stats' => $stats,
        ]);
    }
}
