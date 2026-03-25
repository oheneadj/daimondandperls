<?php

namespace App\Livewire\Booking;

use App\Models\Booking;
use App\Models\Payment;
use App\Notifications\BookingConfirmedNotification;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('components.guest-layout')]
class CheckoutPayment extends Component
{
    public Booking $booking;

    public string $paymentMethod = 'mobile_money'; // default

    // Form fields for Bank Transfer
    public string $senderName = '';

    public string $referenceNotes = '';

    public ?string $errorMessage = null;
    
    // Bank Details from Settings
    public string $bankName = '';
    public string $accountName = '';
    public string $accountNumber = '';
    public string $branchCode = '';

    public ?string $loading = null;

    public function mount(Booking $booking)
    {
        $this->booking = $booking->load(['items.package', 'customer']);

        // Set initial error if persists in session (e.g. from previous redirect)
        if (session()->has('error')) {
            $this->errorMessage = session('error');
        }

        // If already paid or pending verification, redirect to confirmation immediately
        if ($this->booking->payment_status !== \App\Enums\PaymentStatus::Unpaid && $this->booking->payment_status !== \App\Enums\PaymentStatus::Failed) {
            return redirect()->route('booking.confirmation', ['booking' => $this->booking->reference]);
        }

        // Load Bank Details from Settings
        $settings = \App\Models\Setting::where('group', 'bank')->get()->keyBy('key');
        $this->bankName = $settings->get('bank_name')?->value ?? 'Ecobank Ghana';
        $this->accountName = $settings->get('account_name')?->value ?? 'Diamonds & Pearls';
        $this->accountNumber = $settings->get('account_number')?->value ?? '144100XXXXXXX';
        $this->branchCode = $settings->get('branch_code')?->value ?? '';
    }

    public function retry()
    {
        $this->errorMessage = null;
        session()->forget('error');
    }

    public function processMobileMoney()
    {
        $this->loading = 'processMobileMoney';
        DB::transaction(function () {
            // Create a successful dummy payment record
            $payment = Payment::updateOrCreate(
                ['booking_id' => $this->booking->id],
                [
                    'gateway' => 'paystack',
                    'method' => 'mobile_money',
                    'amount' => $this->booking->total_amount,
                    'currency' => 'GHS',
                    'status' => 'successful',
                    'paid_at' => now(),
                    'gateway_reference' => 'MOMO-SIM-'.uniqid(),
                    'gateway_response' => json_encode(['status' => 'success', 'message' => 'Simulated Mobile Money Payment']),
                ]
            );

            $this->booking->update([
                'status' => 'confirmed',
                'payment_status' => 'paid',
            ]);

            $this->booking->customer->notify(new BookingConfirmedNotification(
                $this->booking,
                app(\App\Services\InvoiceService::class)->getDownloadUrl($this->booking)
            ));
        });

        return redirect()->route('booking.confirmation', ['booking' => $this->booking->reference]);
    }

    public function simulateMobileMoneyFailure()
    {
        DB::transaction(function () {
            // Create a failed dummy payment record
            Payment::updateOrCreate(
                ['booking_id' => $this->booking->id],
                [
                    'gateway' => 'paystack',
                    'method' => 'mobile_money',
                    'amount' => $this->booking->total_amount,
                    'currency' => 'GHS',
                    'status' => 'failed',
                    'gateway_reference' => 'MOMO-FAIL-'.uniqid(),
                    'gateway_response' => json_encode(['status' => 'failed', 'message' => 'Insufficient Funds Simulation']),
                ]
            );

            $this->booking->update([
                'payment_status' => 'failed',
            ]);
        });

        $this->errorMessage = 'Payment failed! Insufficient funds. Please try again.';
        session()->flash('error', $this->errorMessage);
    }

    public function processCard()
    {
        $this->loading = 'processCard';
        DB::transaction(function () {
            // Create a successful dummy card payment record
            $payment = Payment::updateOrCreate(
                ['booking_id' => $this->booking->id],
                [
                    'gateway' => 'paystack',
                    'method' => 'card',
                    'amount' => $this->booking->total_amount,
                    'currency' => 'GHS',
                    'status' => 'successful',
                    'paid_at' => now(),
                    'gateway_reference' => 'CARD-SIM-'.uniqid(),
                    'gateway_response' => json_encode(['status' => 'success', 'message' => 'Simulated Card Payment']),
                ]
            );

            $this->booking->update([
                'status' => 'confirmed',
                'payment_status' => 'paid',
            ]);

            $this->booking->customer->notify(new BookingConfirmedNotification(
                $this->booking,
                app(\App\Services\InvoiceService::class)->getDownloadUrl($this->booking)
            ));
        });

        return redirect()->route('booking.confirmation', ['booking' => $this->booking->reference]);
    }

    public function submitBankTransfer()
    {
        $this->loading = 'submitBankTransfer';
        $this->validate([
            'senderName' => 'required|string|max:100',
            'referenceNotes' => 'nullable|string|max:255',
        ]);

        DB::transaction(function () {
            // Create a pending manual payment record
            Payment::updateOrCreate(
                ['booking_id' => $this->booking->id],
                [
                    'gateway' => 'manual',
                    'method' => 'bank_transfer',
                    'amount' => $this->booking->total_amount,
                    'currency' => 'GHS',
                    'status' => 'pending',
                    'gateway_response' => json_encode([
                        'sender_name' => $this->senderName,
                        'notes' => $this->referenceNotes,
                    ]),
                ]
            );

            // Leave booking payment_status as unpaid, but it's now pending manual verification
        });

        return redirect()->route('booking.confirmation', ['booking' => $this->booking->reference]);
    }

    #[Title('Payment Processing')]
    public function render()
    {
        return view('livewire.booking.checkout-payment');
    }
}
