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

    /**
     * Send a one-time password (OTP) to the user's phone number.
     *
     * Resolves or creates a User record using the following priority:
     * 1. Look up by phone number (exact match).
     * 2. If not found, look up by email to avoid duplicate email violations.
     * 3. If still not found, check if a Customer record exists for this phone
     *    and use the customer's email as a fallback lookup.
     * 4. Only create a brand-new User if none of the above matched.
     */
    public function sendOtp(bool $isResend = false): void
    {
        $this->validate([
            'phone' => ['required', 'string', 'regex:/^(?:\+233|0)\d{9}$/'],
        ], [
            'phone.regex' => 'Please enter a valid phone number. This number will be used for payment.',
        ]);

        // Step 1: Try to find an existing user by phone number
        $user = User::query()->where('phone', $this->phone)->first();

        // Step 2: If no user matched by phone, check by email to prevent
        // unique constraint violations (e.g. guest using an admin's email)
        $resolvedEmail = $this->email;
        if (! $user && $resolvedEmail) {
            $user = User::query()->where('email', $resolvedEmail)->first();

            if ($user) {
                // User already exists with this email — attach this phone number to them
                $user->update(['phone' => $this->phone]);
            }
        }

        // Step 3: Still no user — check if a Customer record exists for this phone,
        // and use the customer's email as a final fallback to find an existing user
        if (! $user) {
            $customer = Customer::query()->where('phone', $this->phone)->first();
            $resolvedEmail = $customer?->email ?? $this->email;

            if ($resolvedEmail) {
                $user = User::query()->where('email', $resolvedEmail)->first();

                if ($user) {
                    // Found a user via the customer's email — link phone and customer
                    $user->update(['phone' => $this->phone]);

                    if ($customer) {
                        $customer->update(['user_id' => $user->id]);
                    }
                }
            }

            // Step 4: No existing user found at all — create a brand-new one
            if (! $user) {
                $user = DB::transaction(function () use ($customer, $resolvedEmail): User {
                    $user = User::create([
                        'name' => $customer?->name ?? $this->name ?? 'Customer '.substr($this->phone, -4),
                        'phone' => $this->phone,
                        'email' => $resolvedEmail,
                        'password' => Hash::make(Str::random(32)),
                        'type' => UserType::Customer,
                    ]);

                    // Link to existing customer record, or create a new one
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
        }

        // Generate a 6-digit OTP and save it to the user with a 10-minute expiry
        $otp = (string) rand(100000, 999999);
        $user->update([
            'otp_code' => $otp,
            'otp_expires_at' => now()->addMinutes(10),
        ]);

        $user->notify(new OtpNotification($otp, isResend: $isResend));

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
            'phone_verified_at' => $user->phone_verified_at ?? now(),
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
        $this->sendOtp(isResend: true);
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

    public function getVisualStep(): int
    {
        if (Auth::check() && $this->currentStep > $this->getContactStepNumber()) {
            return $this->currentStep - 1;
        }

        return $this->currentStep;
    }

    public function nextStep(): void
    {
        $this->validateCurrentStep();
        $this->currentStep++;

        if (Auth::check() && $this->currentStep === $this->getContactStepNumber()) {
            $this->currentStep++;
        }
    }

    public function previousStep(): void
    {
        if ($this->currentStep > 1) {
            $this->currentStep--;

            if (Auth::check() && $this->currentStep === $this->getContactStepNumber()) {
                $this->currentStep--;
            }
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
            'phone.required' => 'Please enter a valid phone number. This number will be used for payment and tracking your booking.',
            'phone.regex' => 'Please enter a valid phone number. This number will be used for payment and tracking your booking.',
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
                'scheduled_date' => isset($item['scheduled_date']) ? $item['scheduled_date']?->toDateString() : null,
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

        // Edge case: guest was on the contact step, completed OTP (now authenticated),
        // and was redirected back — advance past the now-skipped contact step.
        if (Auth::check() && $this->currentStep === $this->getContactStepNumber()) {
            $this->currentStep++;
        }

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

    /**
     * Return the step number that represents the Contact Details step.
     * This step is skipped for authenticated users.
     */
    abstract protected function getContactStepNumber(): int;
}
