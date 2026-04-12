<?php

declare(strict_types=1);

namespace App\Livewire\Booking;

use App\Enums\PaymentMethod;
use App\Enums\PaymentStatus;
use App\Models\Booking;
use App\Models\CustomerPaymentMethod;
use App\Models\ErrorLog;
use App\Models\Payment;
use App\Notifications\BookingConfirmedNotification;
use App\Services\CartService;
use App\Services\InvoiceService;
use App\Services\MoolrePaymentService;
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

    // Mobile Money fields
    public string $momoNetwork = '';

    public string $momoNumber = '';

    /** 'form' | 'otp' | 'awaiting' */
    public string $paymentStep = 'form';

    public string $otpCode = '';

    public ?string $otpMessage = null;

    public ?string $errorMessage = null;

    /** Non-retryable error the customer cannot resolve themselves (e.g. merchant config). */
    public ?string $fatalError = null;

    public ?string $loading = null;

    public bool $autoInitiate = false;

    /** @var Collection<int, CustomerPaymentMethod> */
    public Collection $savedMethods;

    public ?int $selectedMethodId = null;

    public bool $useNewNumber = false;

    public function updatedMomoNumber(string $value): void
    {
        $this->momoNumber = preg_replace('/[^0-9]/', '', $value);
    }

    public function getIsMomoFormValidProperty(): bool
    {
        return $this->isValidMomoNumber($this->momoNetwork, $this->momoNumber);
    }

    public function getMomoPlaceholderProperty(): string
    {
        return $this->getMomoPlaceholder($this->momoNetwork);
    }

    public function mount(Booking $booking): void
    {
        $this->booking = $booking->load(['items.package', 'customer']);
        $this->savedMethods = collect();

        if (session()->has('error')) {
            $this->errorMessage = session('error');
        }

        if ($this->booking->payment_status === PaymentStatus::Paid) {
            $this->redirect(route('booking.confirmation', ['booking' => $this->booking->reference]));

            return;
        }

        // Three-state resume detection on page refresh
        if ($this->booking->payment_status === PaymentStatus::Pending && ! empty($this->booking->payment_reference)) {
            // Payment prompt was sent — resume polling
            $this->paymentStep = 'awaiting';
            $this->momoNetwork = $this->booking->payment_channel ?? '';
            $this->momoNumber = $this->booking->payer_number ?? '';
        } elseif ($this->booking->payment_status === PaymentStatus::Unpaid
            && ! empty($this->booking->payment_channel)
            && empty($this->booking->payment_reference)) {
            // OTP was sent but not yet verified — restore OTP entry UI
            $this->paymentStep = 'otp';
            $this->momoNetwork = $this->booking->payment_channel;
            $this->momoNumber = $this->booking->payer_number ?? '';
            $this->otpMessage = 'Enter the verification code sent to your phone to continue.';
        }

        // Pre-populate from checkout session if available (normal flow from /checkout)
        if ($this->paymentStep === 'form') {
            $preSelected = session()->pull('checkout_payment_method');

            if ($preSelected) {
                $this->momoNetwork = $preSelected['network'];
                $this->momoNumber = $preSelected['number'];
                $this->autoInitiate = true;
            } else {
                $this->loadSavedMethods();
            }
        }
    }

    private function loadSavedMethods(): void
    {
        if (Auth::check()) {
            $customer = Auth::user()->customer;

            if ($customer) {
                $this->savedMethods = $customer->paymentMethods()
                    ->where('type', PaymentMethod::MobileMoney->value)
                    ->whereNotNull('verified_at')
                    ->orderByDesc('is_default')
                    ->orderBy('created_at')
                    ->get();
            }

            if ($this->savedMethods->isNotEmpty()) {
                $default = $this->savedMethods->firstWhere('is_default', true)
                    ?? $this->savedMethods->first();

                $this->selectedMethodId = $default->id;
                $this->momoNetwork = $default->provider;
                $this->momoNumber = $default->account_number;
            } else {
                $this->useNewNumber = true;
            }
        } else {
            $this->useNewNumber = true;
        }
    }

    public function selectPaymentMethod(int $id): void
    {
        $method = $this->savedMethods->firstWhere('id', $id);

        if ($method) {
            $this->selectedMethodId = $id;
            $this->useNewNumber = false;
            $this->momoNetwork = $method->provider;
            $this->momoNumber = $method->account_number;
        }
    }

    public function useNewPaymentMethod(): void
    {
        $this->selectedMethodId = null;
        $this->useNewNumber = true;
        $this->momoNetwork = '';
        $this->momoNumber = '';
    }

    public function retry(): void
    {
        $this->errorMessage = null;
        $this->fatalError = null;
        $this->otpCode = '';
        $this->paymentStep = 'form';
        session()->forget('error');
    }

    /**
     * Classify a Moolre error response, log it, and set the appropriate error property.
     * Fatal errors (merchant/gateway config) go to $fatalError; retryable ones to $errorMessage.
     *
     * @param  array<string, mixed>  $response
     */
    private function handlePaymentError(array $response, string $context): void
    {
        $message = $response['message'] ?? 'An unexpected error occurred. Please try again.';
        $code = $response['code'] ?? null;

        Log::error('Moolre payment error', [
            'context' => $context,
            'booking' => $this->booking->reference,
            'code' => $code,
            'message' => $message,
            'response' => $response,
            'network' => $this->momoNetwork,
            'payer' => $this->momoNumber,
        ]);

        ErrorLog::create([
            'source' => 'payment',
            'context' => $context,
            'level' => 'error',
            'booking_reference' => $this->booking->reference,
            'error_code' => $code,
            'message' => $message,
            'network' => $this->momoNetwork,
            'payer_number' => $this->momoNumber,
            'payload' => $response,
        ]);

        // Non-retryable: merchant/gateway configuration issues
        $fatalPhrases = ['merchant account setup', 'account setup incomplete', 'invalid merchant', 'merchant not found'];
        $isFatal = collect($fatalPhrases)->contains(fn (string $phrase) => str_contains(strtolower($message), $phrase));

        if ($isFatal) {
            $this->fatalError = 'Payment is temporarily unavailable. Please contact our support team for assistance.';
        } else {
            $this->errorMessage = $message;
        }
    }

    public function processMobileMoney(MoolrePaymentService $moolre): void
    {
        $this->loading = 'processMobileMoney';
        $this->errorMessage = null;

        $networkPrefixPattern = $this->getNetworkPrefixPattern($this->momoNetwork);

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
        $code = $response['code'] ?? null;

        if ($code === 'TP14') {
            // OTP required — persist channel/number so refresh can restore this step
            $this->booking->update([
                'payment_channel' => $this->momoNetwork,
                'payer_number' => $this->momoNumber,
            ]);

            $this->otpMessage = $response['message'] ?? 'A verification code has been sent to your phone.';
            $this->paymentStep = 'otp';
        } elseif (isset($response['status']) && $response['status'] == 1) {
            // Payment prompt sent directly (no OTP required)
            $this->booking->update([
                'payment_channel' => $this->momoNetwork,
                'payer_number' => $this->momoNumber,
                'payment_status' => PaymentStatus::Pending,
                'payment_reference' => $response['data'] ?? null,
            ]);

            $this->paymentStep = 'awaiting';
        } else {
            $this->handlePaymentError($response, 'initiate');
        }

        $this->loading = null;
    }

    public function submitOtp(MoolrePaymentService $moolre): void
    {
        $this->loading = 'submitOtp';
        $this->errorMessage = null;

        $this->validate([
            'otpCode' => ['required', 'string', 'size:6'],
        ], [
            'otpCode.required' => 'Please enter the 6-digit verification code.',
            'otpCode.size' => 'The verification code must be exactly 6 digits.',
        ]);

        $response = $moolre->submitOtp($this->booking, $this->momoNetwork, $this->momoNumber, $this->otpCode);
        $code = $response['code'] ?? null;

        if ($code === 'TP17') {
            // OTP verified — re-initiate to trigger the actual payment prompt
            $initResponse = $moolre->initiatePayment($this->booking, $this->momoNetwork, $this->momoNumber);

            if (isset($initResponse['status']) && $initResponse['status'] == 1) {
                $this->booking->update([
                    'payment_status' => PaymentStatus::Pending,
                    'payment_reference' => $initResponse['data'] ?? null,
                ]);

                $this->otpCode = '';
                $this->paymentStep = 'awaiting';
            } else {
                $this->handlePaymentError($initResponse, 'otp-reinitiate');
            }
        } elseif ($code === 'TP15') {
            $this->errorMessage = 'Invalid verification code. Please check and try again.';
        } else {
            $this->handlePaymentError($response, 'otp-submit');
        }

        $this->loading = null;
    }

    public function resendOtp(MoolrePaymentService $moolre): void
    {
        $this->errorMessage = null;
        $this->otpCode = '';

        $response = $moolre->initiatePayment($this->booking, $this->momoNetwork, $this->momoNumber);
        $code = $response['code'] ?? null;

        if ($code === 'TP14') {
            $this->otpMessage = 'A new verification code has been sent to your phone.';
        } else {
            $this->handlePaymentError($response, 'resend-otp');
        }
    }

    public function checkPaymentStatus(): void
    {
        $this->booking->refresh();

        if ($this->booking->payment_status === PaymentStatus::Paid) {

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

            $this->booking->customer->notify(new BookingConfirmedNotification(
                $this->booking,
                app(InvoiceService::class)->getDownloadUrl($this->booking)
            ));

            app(CartService::class)->clear();

            // Save the number as a verified payment method for logged-in users who entered a new number
            if (Auth::check() && $this->useNewNumber) {
                $customer = Auth::user()->customer;

                if ($customer) {
                    $networkName = match ($this->momoNetwork) {
                        '13' => 'MTN MoMo',
                        '6' => 'Telecel Cash',
                        '7' => 'AirtelTigo Money',
                        default => 'Mobile Money',
                    };

                    $isFirst = $customer->paymentMethods()->count() === 0;

                    CustomerPaymentMethod::updateOrCreate(
                        ['customer_id' => $customer->id, 'account_number' => $this->momoNumber],
                        [
                            'type' => PaymentMethod::MobileMoney->value,
                            'label' => $networkName.' - '.$this->momoNumber,
                            'provider' => $this->momoNetwork,
                            'is_default' => $isFirst,
                            'verified_at' => now(),
                        ]
                    );
                }
            }

            $this->redirect(route('booking.confirmation', ['booking' => $this->booking->reference]));

            return;
        }

        if ($this->booking->payment_status === PaymentStatus::Failed) {
            Log::warning('Moolre payment failed via webhook/poll', [
                'booking' => $this->booking->reference,
                'network' => $this->momoNetwork,
                'payer' => $this->momoNumber,
                'payment_details' => $this->booking->payment_details,
            ]);

            ErrorLog::create([
                'source' => 'payment',
                'context' => 'webhook-failed',
                'level' => 'warning',
                'booking_reference' => $this->booking->reference,
                'message' => 'Payment was declined or failed (reported by Moolre webhook/poll).',
                'network' => $this->momoNetwork,
                'payer_number' => $this->momoNumber,
                'payload' => $this->booking->payment_details ?? [],
            ]);

            $this->paymentStep = 'form';
            $this->errorMessage = 'Payment was declined or failed. Please try again.';
            session()->flash('error', $this->errorMessage);
        }
    }

    public function cancelPayment(): void
    {
        $this->paymentStep = 'form';
        $this->otpCode = '';

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
