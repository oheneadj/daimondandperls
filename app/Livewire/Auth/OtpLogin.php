<?php

declare(strict_types=1);

namespace App\Livewire\Auth;

use App\Enums\UserType;
use App\Models\Customer;
use App\Models\User;
use App\Notifications\OtpNotification;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Livewire\Component;

class OtpLogin extends Component
{
    public string $phone = '';

    public string $otp = '';

    public int $step = 1;

    public string $error = '';

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
                    'name' => $customer?->name ?? 'Customer '.substr($this->phone, -4),
                    'phone' => $this->phone,
                    'email' => $customer?->email,
                    'password' => Hash::make(Str::random(32)),
                    'type' => UserType::Customer,
                ]);

                if ($customer) {
                    $customer->update(['user_id' => $user->id]);
                } else {
                    $user->customer()->create([
                        'name' => $user->name,
                        'phone' => $this->phone,
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

        $this->step = 2;
        $this->error = '';
    }

    public function verifyOtp(): void
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
            $this->error = 'Invalid or expired OTP code.';

            return;
        }

        $user->update([
            'otp_code' => null,
            'otp_expires_at' => null,
        ]);

        Auth::login($user, true);

        $this->redirect(route('dashboard.index'));
    }

    public function resendOtp(): void
    {
        $this->otp = '';
        $this->error = '';
        $this->sendOtp();
    }

    public function backToPhone(): void
    {
        $this->step = 1;
        $this->otp = '';
        $this->error = '';
    }

    public function render(): View
    {
        return view('livewire.auth.otp-login');
    }
}
