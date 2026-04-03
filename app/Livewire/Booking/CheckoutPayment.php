<?php

namespace App\Livewire\Booking;

use App\Enums\PaymentStatus;
use App\Models\Booking;
use App\Models\Payment;
use App\Notifications\BookingConfirmedNotification;
use App\Services\MoolrePaymentService;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('components.guest-layout')]
class CheckoutPayment extends Component
{
    public Booking $booking;

    public string $paymentMethod = 'mobile_money';

    // Mobile Money fields
    public string $momoNetwork = '';

    public string $momoNumber = '';

    public bool $isAwaitingPayment = false;

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

    /**
     * Strip non-numeric characters as user types.
     */
    public function updatedMomoNumber(string $value): void
    {
        $this->momoNumber = preg_replace('/[^0-9]/', '', $value);
    }

    /**
     * Computed: is the MoMo form valid enough to enable the Pay button?
     */
    public function getIsMomoFormValidProperty(): bool
    {
        if (empty($this->momoNetwork) || strlen($this->momoNumber) !== 10) {
            return false;
        }

        return (bool) preg_match($this->getNetworkPrefixPattern(), $this->momoNumber);
    }

    /**
     * Returns the regex pattern matching valid prefixes for the selected network.
     */
    private function getNetworkPrefixPattern(): string
    {
        return match ($this->momoNetwork) {
            '13' => '/^0(24|54|55|59)\d{7}$/',  // MTN
            '6' => '/^0(20|50)\d{7}$/',         // Telecel
            '7' => '/^0(26|56|27|57)\d{7}$/',   // AT
            default => '/^0\d{9}$/',
        };
    }

    public function mount(Booking $booking)
    {
        $this->booking = $booking->load(['items.package', 'customer']);

        if (session()->has('error')) {
            $this->errorMessage = session('error');
        }

        if ($this->booking->payment_status === PaymentStatus::Paid) {
            return redirect()->route('booking.confirmation', ['booking' => $this->booking->reference]);
        }

        // Auto-resume polling if already initialized (e.g., page refresh)
        if ($this->booking->payment_status === PaymentStatus::Pending && ! empty($this->booking->payment_reference)) {
            $this->isAwaitingPayment = true;
            $this->paymentMethod = 'mobile_money';
            $this->momoNetwork = $this->booking->payment_channel ?? '';
            $this->momoNumber = $this->booking->payer_number ?? '';
        }

        // Prefill from user if applicable
        if (\Illuminate\Support\Facades\Auth::check()) {
            $user = \Illuminate\Support\Facades\Auth::user();
            $this->momoNumber = $user->saved_momo_number ?? $this->momoNumber;
            $this->momoNetwork = $user->saved_momo_network ?? $this->momoNetwork;
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
        $this->isAwaitingPayment = false;
    }

    public function processMobileMoney(MoolrePaymentService $moolre)
    {
        $this->loading = 'processMobileMoney';
        $this->errorMessage = null;

        $networkPrefixPattern = $this->getNetworkPrefixPattern();

        $this->validate([
            'momoNetwork' => 'required|in:13,6,7',
            'momoNumber' => ['required', 'regex:'.$networkPrefixPattern],
        ], [
            'momoNetwork.required' => 'Please select your mobile network.',
            'momoNetwork.in' => 'Invalid network selected.',
            'momoNumber.required' => 'Please provide your mobile money number.',
            'momoNumber.regex' => 'This number doesn\'t match the selected network. Please check the prefix.',
        ]);

        $response = $moolre->initiatePayment($this->booking, $this->momoNetwork, $this->momoNumber);

        if (isset($response['status']) && $response['status'] == 1) {
            $this->booking->update([
                'payment_channel' => $this->momoNetwork,
                'payer_number' => $this->momoNumber,
                'payment_status' => PaymentStatus::Pending,
                'payment_reference' => $response['data'] ?? null,
            ]);


            $this->isAwaitingPayment = true;
        } else {
            $this->errorMessage = $response['message'] ?? 'Failed to initiate payment prompt. Please try again.';
            session()->flash('error', $this->errorMessage);
        }

        $this->loading = null;
    }

    public function checkPaymentStatus()
    {
        $this->booking->refresh();

        if ($this->booking->payment_status === PaymentStatus::Paid) {

            // Generate robust Payment record mirroring dummy card model logic
            Payment::updateOrCreate(
                ['booking_id' => $this->booking->id],
                [
                    'gateway' => 'moolre',
                    'method' => 'mobile_money',
                    'amount' => $this->booking->total_amount,
                    'currency' => 'GHS',
                    'status' => 'successful',
                    'paid_at' => now(),
                    'gateway_reference' => $this->booking->payment_reference,
                    'gateway_response' => json_encode($this->booking->payment_details ?? []),
                ]
            );

            // Notify customer of paid success
            $this->booking->customer->notify(new BookingConfirmedNotification(
                $this->booking,
                app(\App\Services\InvoiceService::class)->getDownloadUrl($this->booking)
            ));

            // Save as default payment method for logged-in users
            if (\Illuminate\Support\Facades\Auth::check()) {
                \Illuminate\Support\Facades\Auth::user()->update([
                    'saved_momo_number' => $this->momoNumber,
                    'saved_momo_network' => $this->momoNetwork,
                ]);
            }

            return redirect()->route('booking.confirmation', ['booking' => $this->booking->reference]);
        }

        if ($this->booking->payment_status === PaymentStatus::Failed) {
            $this->isAwaitingPayment = false;
            $this->errorMessage = 'Payment was declined or failed. Please try again.';
            session()->flash('error', $this->errorMessage);
        }
    }

    public function cancelPayment()
    {
        $this->isAwaitingPayment = false;
        $this->booking->update([
            'payment_status' => PaymentStatus::Unpaid,
            'payment_reference' => null,
        ]);
    }

    public function processCard()
    {
        $this->loading = 'processCard';
        DB::transaction(function () {
            Payment::updateOrCreate(
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
                'payment_status' => PaymentStatus::Paid,
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
        });

        return redirect()->route('booking.confirmation', ['booking' => $this->booking->reference]);
    }

    #[Title('Payment Processing')]
    public function render()
    {
        return view('livewire.booking.checkout-payment');
    }
}
