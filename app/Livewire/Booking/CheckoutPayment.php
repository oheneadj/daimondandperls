<?php

declare(strict_types=1);

namespace App\Livewire\Booking;

use App\Contracts\PaymentGatewayContract;
use App\Enums\PaymentStatus;
use App\Models\Booking;
use App\Models\ErrorLog;
use App\Traits\HandlesMomoValidation;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('components.guest-layout')]
class CheckoutPayment extends Component
{
    use HandlesMomoValidation;

    public Booking $booking;

    /** 'form' | 'awaiting' */
    public string $paymentStep = 'form';

    public ?string $errorMessage = null;

    /** Non-retryable error the customer cannot resolve themselves (e.g. merchant config). */
    public ?string $fatalError = null;

    public ?string $loading = null;

    // Payment method selection
    /** 'saved' | 'new_momo' | 'card' | '' */
    public string $paymentChoice = '';

    public ?int $selectedMethodId = null;

    /** @var Collection<int, \App\Models\CustomerPaymentMethod> */
    public Collection $savedMethods;

    public bool $showNewMomoForm = false;

    // New number entry
    public string $momoNetwork = '';

    public string $momoNumber = '';

    public bool $saveNewMethod = true;

    public function updatedMomoNumber(string $value): void
    {
        $this->momoNumber = preg_replace('/[^0-9]/', '', $value);
    }

    public function getIsMomoFormValidProperty(): bool
    {
        return $this->isValidMomoNumber($this->momoNetwork, $this->momoNumber);
    }

    public function mount(Booking $booking): void
    {
        $this->booking = $booking->load(['items.package', 'customer']);
        $this->savedMethods = collect();

        if (session()->has('error')) {
            $this->errorMessage = session('error');
        }

        if (session()->pull('payment_awaiting')) {
            $this->paymentStep = 'awaiting';

            return;
        }

        if ($this->booking->payment_status === PaymentStatus::Paid) {
            $this->redirect(route('booking.confirmation', ['booking' => $this->booking->reference]));

            return;
        }

        if ($this->booking->payment_status === PaymentStatus::Pending && ! empty($this->booking->payment_reference)) {
            $this->paymentStep = 'awaiting';

            return;
        }

        // Load saved verified MoMo methods for logged-in users
        if (Auth::check()) {
            $customer = Auth::user()->customer;

            if ($customer) {
                $this->savedMethods = $customer->paymentMethods()
                    ->where('type', \App\Enums\PaymentMethod::MobileMoney->value)
                    ->whereNotNull('verified_at')
                    ->orderByDesc('is_default')
                    ->orderBy('created_at')
                    ->get();
            }

            if ($this->savedMethods->isNotEmpty()) {
                $default = $this->savedMethods->firstWhere('is_default', true)
                    ?? $this->savedMethods->first();

                $this->paymentChoice = 'saved';
                $this->selectedMethodId = $default->id;
            }
        }
    }

    public function selectPaymentMethod(int $id): void
    {
        $method = $this->savedMethods->firstWhere('id', $id);

        if ($method) {
            $this->selectedMethodId = $id;
            $this->paymentChoice = 'saved';
            $this->showNewMomoForm = false;
        }
    }

    public function toggleNewMomoForm(): void
    {
        $this->showNewMomoForm = ! $this->showNewMomoForm;
        $this->paymentChoice = $this->showNewMomoForm ? 'new_momo' : 'saved';

        if (! $this->showNewMomoForm) {
            $default = $this->savedMethods->firstWhere('is_default', true)
                ?? $this->savedMethods->first();
            if ($default) {
                $this->selectedMethodId = $default->id;
            }
        }
    }

    public function selectCard(): void
    {
        $this->paymentChoice = 'card';
        $this->showNewMomoForm = false;
    }

    /**
     * Start payment — builds context from the current payment choice and calls Transflow.
     */
    public function initiateCheckout(PaymentGatewayContract $gateway): void
    {
        $this->loading = 'initiateCheckout';
        $this->errorMessage = null;

        $context = $this->buildPaymentContext();

        if ($context === false) {
            // Validation failed (invalid MoMo number)
            $this->loading = null;

            return;
        }

        // Store network/number on booking so the return controller can save the method
        if ($this->paymentChoice === 'new_momo') {
            $this->booking->update([
                'payment_channel' => $this->momoNetwork,
                'payer_number' => $this->momoNumber,
            ]);
        }

        $result = $gateway->initiate($this->booking, $context);

        if ($result->isRedirect()) {
            $this->booking->update([
                'payment_reference' => $result->reference,
                'payment_status' => PaymentStatus::Pending,
            ]);

            $this->redirect($result->redirectUrl);

            return;
        }

        // Gateway returned an error
        $message = $result->raw['responseMessage'] ?? ($result->message ?? 'Payment initiation failed. Please try again.');

        Log::error('Payment initiation error', [
            'context' => 'initiate-checkout',
            'booking' => $this->booking->reference,
            'message' => $message,
            'response' => $result->raw ?? [],
        ]);

        ErrorLog::create([
            'source' => 'payment',
            'context' => 'initiate-checkout',
            'level' => 'error',
            'booking_reference' => $this->booking->reference,
            'message' => $message,
            'payload' => $result->raw ?? [],
        ]);

        $fatalPhrases = ['merchant account setup', 'account setup incomplete', 'invalid merchant', 'merchant not found'];
        $isFatal = collect($fatalPhrases)->contains(fn (string $phrase) => str_contains(strtolower($message), $phrase));

        if ($isFatal) {
            $this->fatalError = 'Payment is temporarily unavailable. Please contact our support team for assistance.';
        } else {
            $this->errorMessage = $message;
        }

        $this->loading = null;
    }

    /**
     * Build the payment context array for Transflow based on the current payment choice.
     * Returns false if validation fails.
     *
     * @return array<string, mixed>|false
     */
    private function buildPaymentContext(): array|false
    {
        if ($this->paymentChoice === 'saved') {
            $method = $this->savedMethods->firstWhere('id', $this->selectedMethodId);

            if (! $method) {
                $this->errorMessage = 'Please select a payment method.';

                return false;
            }

            return [
                'payment_method' => 'mobile_money',
                'msisdn' => $method->account_number,
                'network' => $this->mapToTransflowNetwork($method->provider),
            ];
        }

        if ($this->paymentChoice === 'new_momo') {
            $networkPrefixPattern = $this->getNetworkPrefixPattern($this->momoNetwork);

            $validationResult = validator(
                ['momoNetwork' => $this->momoNetwork, 'momoNumber' => $this->momoNumber],
                [
                    'momoNetwork' => 'required|in:13,6,7',
                    'momoNumber' => ['required', 'regex:'.$networkPrefixPattern],
                ],
                [
                    'momoNetwork.required' => 'Please select your mobile network.',
                    'momoNetwork.in' => 'Invalid network selected.',
                    'momoNumber.required' => 'Please provide your mobile money number.',
                    'momoNumber.regex' => "This number doesn't match the selected network.",
                ]
            );

            if ($validationResult->fails()) {
                $this->errorMessage = $validationResult->errors()->first();

                return false;
            }

            return [
                'payment_method' => 'mobile_money',
                'msisdn' => $this->momoNumber,
                'network' => $this->mapToTransflowNetwork($this->momoNetwork),
            ];
        }

        if ($this->paymentChoice === 'card') {
            return ['payment_method' => 'card'];
        }

        // Guest / no pre-selection — let Transflow show all options
        return [];
    }

    /**
     * Map the stored provider code to Transflow's network name.
     */
    private function mapToTransflowNetwork(string $provider): string
    {
        return match ($provider) {
            '13' => 'MTN',
            '6' => 'VODAFONE',
            '7' => 'AIRTELTIGO',
            default => '',
        };
    }

    public function checkPaymentStatus(): void
    {
        $this->booking->refresh();

        if ($this->booking->payment_status === PaymentStatus::Paid) {
            app(\App\Services\CartService::class)->clear();

            $this->redirect(route('booking.confirmation', ['booking' => $this->booking->reference]));

            return;
        }

        if ($this->booking->payment_status === PaymentStatus::Failed) {
            Log::warning('Payment failed via webhook/poll', [
                'booking' => $this->booking->reference,
                'payment_details' => $this->booking->payment_details,
            ]);

            ErrorLog::create([
                'source' => 'payment',
                'context' => 'webhook-failed',
                'level' => 'warning',
                'booking_reference' => $this->booking->reference,
                'message' => 'Payment was declined or failed.',
                'payload' => $this->booking->payment_details ?? [],
            ]);

            $this->paymentStep = 'form';
            $this->errorMessage = 'Payment was declined or failed. Please try again.';
            session()->flash('error', $this->errorMessage);
        }
    }

    public function retry(): void
    {
        $this->errorMessage = null;
        $this->fatalError = null;
        $this->paymentStep = 'form';
        session()->forget('error');
    }

    public function cancelPayment(): void
    {
        $this->paymentStep = 'form';

        $this->booking->update([
            'payment_status' => PaymentStatus::Unpaid,
            'payment_reference' => null,
            'payment_channel' => null,
            'payer_number' => null,
        ]);
    }

    #[Title('Payment Processing')]
    public function render(): View
    {
        return view('livewire.booking.checkout-payment');
    }
}
