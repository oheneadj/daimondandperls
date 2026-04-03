<?php

declare(strict_types=1);

namespace App\Traits;

use App\Enums\BookingStatus;
use App\Enums\PaymentStatus;
use App\Enums\UserRole;
use App\Enums\UserType;
use App\Models\Booking;
use App\Models\Customer;
use App\Models\User;
use App\Notifications\BookingReceivedNotification;
use App\Notifications\CustomerBookingReceivedNotification;
use App\Notifications\OtpNotification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Str;

trait HandlesBookingCheckout
{
    public int $currentStep = 1;

    public ?string $loading = null;

    // Contact Information
    public ?string $name = null;

    public ?string $phone = null;

    public ?string $email = null;

    // Phone verification (OTP)
    public bool $verifyPhone = false;

    public string $otp = '';

    public int $otpStep = 0;

    public string $otpError = '';

    public function sendOtp(): void
    {
        $this->validate([
            'phone' => ['required', 'string', 'regex:/^(?:\+233|0)\d{9}$/'],
        ], [
            'phone.regex' => 'Please enter a valid Ghana phone number (e.g. 0244000000).',
        ]);

        $user = User::query()->where('phone', $this->phone)->first();

        if (! $user) {
            $customer = Customer::query()->where('phone', $this->phone)->first();

            $user = DB::transaction(function () use ($customer): User {
                $user = User::create([
                    'name' => $customer?->name ?? $this->name ?? 'Customer '.substr($this->phone, -4),
                    'phone' => $this->phone,
                    'email' => $customer?->email ?? $this->email,
                    'password' => Hash::make(Str::random(32)),
                    'type' => UserType::Customer,
                ]);

                if ($customer) {
                    $customer->update(['user_id' => $user->id]);
                } else {
                    $user->customer()->create([
                        'name' => $user->name,
                        'phone' => $this->phone,
                        'email' => $this->email,
                    ]);
                }

                return $user;
            });
        }

        $otp = (string) rand(100000, 999999);
        $user->update([
            'otp_code' => $otp,
            'otp_expires_at' => now()->addMinutes(10),
        ]);

        $user->notify(new OtpNotification($otp));

        $this->otpStep = 2;
        $this->otpError = '';
    }

    public function verifyOtp(): mixed
    {
        $this->validate([
            'otp' => ['required', 'string', 'size:6'],
        ]);

        $user = User::query()
            ->where('phone', $this->phone)
            ->where('otp_code', $this->otp)
            ->where('otp_expires_at', '>', now())
            ->first();

        if (! $user) {
            $this->otpError = 'Invalid or expired OTP code.';

            return null;
        }

        $user->update([
            'otp_code' => null,
            'otp_expires_at' => null,
        ]);

        Auth::login($user, true);

        $name = $user->name;
        $email = $user->email ?? $this->email;

        $customer = $user->customer;
        if ($customer) {
            $name = $customer->name ?: $name;
            $email = $customer->email ?: $email;
        }

        $this->saveWizardState([
            'name' => $name,
            'phone' => $this->phone,
            'email' => $email,
        ]);

        return $this->redirect($this->getRedirectRoute());
    }

    public function resendOtp(): void
    {
        $this->otp = '';
        $this->otpError = '';
        $this->sendOtp();
    }

    public function cancelOtp(): void
    {
        $this->otpStep = 0;
        $this->otp = '';
        $this->otpError = '';
        $this->verifyPhone = false;
    }

    public function updated(string $propertyName): void
    {
        $type = (new \ReflectionProperty($this, $propertyName))->getType();

        if ($this->$propertyName === '' && $type?->allowsNull()) {
            $this->$propertyName = null;
        }
    }

    public function nextStep(): void
    {
        $this->validateCurrentStep();
        $this->currentStep++;
    }

    public function previousStep(): void
    {
        if ($this->currentStep > 1) {
            $this->currentStep--;
        }
    }

    protected function prefillFromAuth(): void
    {
        if (Auth::check()) {
            $user = Auth::user();
            $this->name = $user->name;
            $this->email = $user->email;
            $this->phone = $user->phone;
        }
    }

    protected function validateContactInfo(): void
    {
        $this->validate([
            'name' => ['required', 'string', 'max:100', 'min:3'],
            'phone' => ['required', 'regex:/^(?:\+233|0)\d{9}$/'],
            'email' => ['nullable', 'email', 'max:150'],
        ], [
            'phone.regex' => 'Please enter a valid Ghanaian phone number (e.g. 024XXXXXXX or +23324XXXXXXX).',
        ]);
    }

    protected function resolveCustomer(): Customer
    {
        $customer = Customer::query()->where(['phone' => $this->phone])->first();

        if (Auth::check()) {
            $user = Auth::user();

            if ($customer) {
                $customer->update([
                    'user_id' => $user->id,
                    'name' => $this->name,
                    'email' => $this->email,
                ]);
            } else {
                $customer = $user->customer()->create([
                    'name' => $this->name,
                    'phone' => $this->phone,
                    'email' => $this->email,
                ]);
            }
        } elseif (! $customer) {
            $customer = Customer::query()->create([
                'phone' => $this->phone,
                'name' => $this->name,
                'email' => $this->email,
            ]);
        } else {
            $customer->update([
                'name' => $this->name,
                'email' => $this->email,
            ]);
        }

        return $customer;
    }

    protected function findDuplicateBooking(int $customerId, float $totalAmount): ?Booking
    {
        return Booking::where('customer_id', $customerId)
            ->where('status', BookingStatus::Pending)
            ->where('payment_status', PaymentStatus::Unpaid)
            ->where('total_amount', $totalAmount)
            ->where('created_at', '>=', now()->subMinutes(15))
            ->first();
    }

    protected function saveCartItemsToBooking(Booking $booking, iterable $cartItems): void
    {
        foreach ($cartItems as $item) {
            $booking->items()->create([
                'package_id' => $item['package']->id,
                'package_name' => $item['package']->name,
                'package_description' => $item['package']->description,
                'price' => $item['package']->price,
                'quantity' => $item['quantity'],
            ]);
        }
    }

    protected function notifyBookingCreated(Booking $booking, Customer $customer): void
    {
        $admins = User::query()
            ->whereIn('role', [UserRole::Admin, UserRole::SuperAdmin])
            ->where(['is_active' => true])
            ->get();

        Notification::send($admins, new BookingReceivedNotification($booking));
        $customer->notify(new CustomerBookingReceivedNotification($booking));
    }

    protected function generateReference(): string
    {
        $year = date('Y');

        $latestBooking = Booking::where('reference', 'like', "CAT-{$year}-%")->orderBy('id', 'desc')->first();

        $sequence = 1;
        if ($latestBooking) {
            $parts = explode('-', $latestBooking->reference);
            if (count($parts) === 3) {
                $sequence = intval($parts[2]) + 1;
            }
        }

        do {
            $reference = sprintf('CAT-%s-%05d', $year, $sequence);
            $exists = Booking::where('reference', $reference)->exists();
            if ($exists) {
                $sequence++;
            }
        } while ($exists);

        return $reference;
    }

    /**
     * Save wizard state to session for OTP redirect survival.
     * Override in each wizard to include wizard-specific fields.
     */
    protected function saveWizardState(array $overrides = []): void
    {
        $state = array_merge([
            'currentStep' => $this->currentStep,
            'name' => $this->name,
            'phone' => $this->phone,
            'email' => $this->email,
        ], $this->getWizardSpecificState(), $overrides);

        session()->put('checkout_wizard_state', $state);
    }

    protected function restoreWizardState(): bool
    {
        if (! session()->has('checkout_wizard_state')) {
            return false;
        }

        $state = session()->pull('checkout_wizard_state');
        $this->currentStep = $state['currentStep'] ?? 1;
        $this->name = $state['name'] ?? null;
        $this->phone = $state['phone'] ?? null;
        $this->email = $state['email'] ?? null;

        $this->restoreWizardSpecificState($state);

        return true;
    }

    /**
     * Override in each wizard to return wizard-specific state for session persistence.
     */
    protected function getWizardSpecificState(): array
    {
        return [];
    }

    /**
     * Override in each wizard to restore wizard-specific state from session.
     */
    protected function restoreWizardSpecificState(array $state): void {}

    /**
     * Override in each wizard to validate the current step before advancing.
     */
    abstract protected function validateCurrentStep(): void;

    /**
     * Override in each wizard to return the redirect route after OTP verification.
     */
    abstract protected function getRedirectRoute(): string;
}
