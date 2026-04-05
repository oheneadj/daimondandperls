<?php

declare(strict_types=1);

namespace App\Livewire\Customer;

use App\Enums\PaymentMethod;
use App\Notifications\OtpNotification;
use App\Traits\HandlesMomoValidation;
use App\Traits\ResolvesCustomer;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.customer')]
#[Title('Payment Methods')]
class PaymentMethods extends Component
{
    use HandlesMomoValidation, ResolvesCustomer;

    public bool $showForm = false;

    public bool $showOtpModal = false;

    public ?int $editingId = null;

    public ?int $verifyingId = null;

    public string $otpCode = '';

    public string $type = '';

    public string $label = '';

    public string $provider = '';

    public string $accountNumber = '';

    public string $accountName = '';

    public bool $isDefault = false;

    public function rules(): array
    {
        $rules = [
            'type' => ['required', Rule::in(array_column($this->allowedTypes(), 'value'))],
            'label' => ['required', 'string', 'max:100'],
            'isDefault' => ['boolean'],
            'accountName' => ['nullable', 'string', 'max:100'],
        ];

        if ($this->type === PaymentMethod::MobileMoney->value) {
            $rules['provider'] = ['required', 'in:13,6,7'];
            $rules['accountNumber'] = ['required', 'regex:'.$this->getNetworkPrefixPattern($this->provider)];
        } else {
            $rules['provider'] = ['nullable', 'string', 'max:50'];
            $rules['accountNumber'] = ['required', 'string', 'max:50'];
        }

        return $rules;
    }

    public function messages(): array
    {
        return [
            'type.required' => 'Please select a payment type.',
            'label.required' => 'Please provide a label for this payment method.',
            'provider.required' => 'Please select your mobile network.',
            'accountNumber.required' => 'Please enter the mobile money number.',
            'accountNumber.regex' => 'This number doesn\'t match the selected network prefix.',
        ];
    }

    public function getIsMomoFormValidProperty(): bool
    {
        return $this->isValidMomoNumber($this->provider, $this->accountNumber);
    }

    public function getMomoPlaceholderProperty(): string
    {
        return $this->getMomoPlaceholder($this->provider);
    }

    public function updatedAccountNumber(string $value): void
    {
        if ($this->type === PaymentMethod::MobileMoney->value) {
            $this->accountNumber = preg_replace('/[^0-9]/', '', $value);
        }
    }

    public function openForm(): void
    {
        $this->resetForm();
        $this->showForm = true;
    }

    public function edit(int $id): void
    {
        $customer = $this->resolveCustomer();
        $method = $customer->paymentMethods()->findOrFail($id);

        $this->editingId = $method->id;
        $this->type = $method->type->value;
        $this->label = $method->label;
        $this->provider = $method->provider ?? '';
        $this->accountNumber = $method->account_number;
        $this->accountName = $method->account_name ?? '';
        $this->isDefault = $method->is_default;
        $this->showForm = true;
    }

    public function save(): void
    {
        $this->validate();

        $customer = $this->resolveCustomer();

        if ($this->isDefault) {
            $customer->paymentMethods()->update(['is_default' => false]);
        }

        $data = [
            'type' => $this->type,
            'label' => $this->label,
            'provider' => $this->provider ?: null,
            'account_number' => $this->accountNumber,
            'account_name' => $this->accountName ?: null,
            'is_default' => $this->isDefault,
        ];

        if ($this->editingId) {
            /** @var \App\Models\CustomerPaymentMethod $existing */
            $existing = $customer->paymentMethods()->findOrFail($this->editingId);
            $accountChanged = $existing->account_number !== $this->accountNumber
                || $existing->provider !== ($this->provider ?: null);

            if ($accountChanged) {
                $otp = (string) random_int(100000, 999999);
                $data['verification_code'] = Hash::make($otp);
                $data['verified_at'] = null;
                $existing->update($data);

                $customer->notify(new OtpNotification($otp, 'payment_method'));

                $this->verifyingId = $existing->id;
                $this->showOtpModal = true;
                $message = 'Account changed. Please re-verify with OTP.';
            } else {
                $existing->update($data);
                $message = 'Payment method updated.';
            }
        } else {
            if ($customer->paymentMethods()->count() === 0) {
                $data['is_default'] = true;
                $this->isDefault = true;
            }

            $otp = (string) random_int(100000, 999999);
            $data['verification_code'] = Hash::make($otp);
            $method = $customer->paymentMethods()->create($data);

            $customer->notify(new OtpNotification($otp, 'payment_method'));

            $this->verifyingId = $method->id;
            $this->showOtpModal = true;
            $message = 'Payment method added. Please verify with OTP.';
        }

        if (! $this->showOtpModal) {
            $this->showForm = false;
            $this->resetForm();
        }

        $this->dispatch('toast', [
            'type' => 'success',
            'message' => $message,
        ]);
    }

    public function verifyOtp(): void
    {
        $this->validate(['otpCode' => ['required', 'string', 'size:6']]);

        $customer = $this->resolveCustomer();

        $key = 'verify-otp:'.$customer->id;
        if (RateLimiter::tooManyAttempts($key, 5)) {
            $seconds = RateLimiter::availableIn($key);
            $this->addError('otpCode', "Too many attempts. Try again in {$seconds} seconds.");

            return;
        }

        /** @var \App\Models\CustomerPaymentMethod $method */
        $method = $customer->paymentMethods()->findOrFail($this->verifyingId);

        if (Hash::check($this->otpCode, $method->verification_code ?? '')) {
            RateLimiter::clear($key);

            $method->update([
                'verified_at' => now(),
                'verification_code' => null,
            ]);

            $this->showOtpModal = false;
            $this->showForm = false;
            $this->verifyingId = null;
            $this->otpCode = '';
            $this->resetForm();

            $this->dispatch('toast', [
                'type' => 'success',
                'message' => 'Payment method verified successfully!',
            ]);
        } else {
            RateLimiter::hit($key, 300);
            $remaining = 5 - RateLimiter::attempts($key);
            $this->addError('otpCode', "Incorrect code. {$remaining} attempts remaining.");
        }
    }

    public function resendOtp(int $id): void
    {
        $customer = $this->resolveCustomer();

        $key = 'resend-otp:'.$customer->id;
        if (RateLimiter::tooManyAttempts($key, 1)) {
            $seconds = RateLimiter::availableIn($key);
            $this->dispatch('toast', [
                'type' => 'error',
                'message' => "Please wait {$seconds} seconds before resending.",
            ]);

            return;
        }

        RateLimiter::hit($key, 60);

        /** @var \App\Models\CustomerPaymentMethod $method */
        $method = $customer->paymentMethods()->findOrFail($id);

        $newCode = (string) random_int(100000, 999999);
        $method->update(['verification_code' => Hash::make($newCode)]);

        $customer->notify(new OtpNotification($newCode, 'payment_method'));

        $this->verifyingId = $method->id;
        $this->otpCode = '';
        $this->showOtpModal = true;

        $this->dispatch('toast', [
            'type' => 'info',
            'message' => 'New verification code sent.',
        ]);
    }

    public function setDefault(int $id): void
    {
        $customer = $this->resolveCustomer();
        $customer->paymentMethods()->update(['is_default' => false]);
        $customer->paymentMethods()->where('id', $id)->update(['is_default' => true]);

        $this->dispatch('toast', [
            'type' => 'success',
            'message' => 'Default payment method updated.',
        ]);
    }

    public function delete(int $id): void
    {
        $customer = $this->resolveCustomer();
        $method = $customer->paymentMethods()->findOrFail($id);
        $wasDefault = $method->is_default;
        $method->delete();

        if ($wasDefault) {
            $customer->paymentMethods()->oldest()->first()?->update(['is_default' => true]);
        }

        $this->dispatch('toast', [
            'type' => 'success',
            'message' => 'Payment method removed.',
        ]);
    }

    public function cancel(): void
    {
        $this->showForm = false;
        $this->showOtpModal = false;
        $this->verifyingId = null;
        $this->otpCode = '';
        $this->resetForm();
        $this->resetValidation();
    }

    public function render(): View
    {
        $customer = $this->resolveCustomer();

        return view('livewire.customer.payment-methods', [
            'methods' => $customer?->paymentMethods()->latest()->get() ?? collect(),
            'allowedTypes' => $this->allowedTypes(),
        ]);
    }

    /** @return list<PaymentMethod> */
    private function allowedTypes(): array
    {
        return [
            PaymentMethod::MobileMoney,
            // Card and Bank Transfer disabled for now
            // PaymentMethod::Card,
            // PaymentMethod::BankTransfer,
        ];
    }

    private function resetForm(): void
    {
        $this->editingId = null;
        $this->type = PaymentMethod::MobileMoney->value;
        $this->label = '';
        $this->provider = '13'; // Default to MTN
        $this->accountNumber = '';
        $this->accountName = '';
        $this->isDefault = false;
    }
}
