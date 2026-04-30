<?php

declare(strict_types=1);

namespace App\Livewire\Auth;

use App\Enums\UserType;
use App\Models\User;
use App\Notifications\OtpNotification;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class OtpLogin extends Component
{
    public string $phone = '';

    public string $otp = '';

    public int $step = 1;

    public string $error = '';

    public function sendOtp(bool $isResend = false): void
    {
        $this->validate([
            'phone' => ['required', 'string', 'regex:/^(?:\+233|0)\d{9}$/'],
        ], [
            'phone.regex' => 'Please enter a valid Ghana phone number (e.g. 0244000000).',
        ]);

        $user = User::query()->where('phone', $this->phone)->first();

        if (! $user) {
            $this->error = 'No account found with this phone number. Please register first.';

            return;
        }

        if ($user->type === UserType::Admin) {
            $this->error = 'No account found with this phone number. Please register first.';

            return;
        }

        if (! $user->is_active) {
            $this->error = 'Your account has been disabled. Please contact an administrator for assistance.';

            return;
        }

        $otp = (string) random_int(100000, 999999);
        $user->update([
            'otp_code' => $otp,
            'otp_expires_at' => now()->addMinutes(10),
        ]);

        $user->notify(new OtpNotification($otp, isResend: $isResend));

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

        if (! $user->is_active) {
            $this->error = 'Your account has been disabled. Please contact an administrator for assistance.';

            return;
        }

        $user->update([
            'otp_code' => null,
            'otp_expires_at' => null,
        ]);

        Auth::login($user, true);

        $destination = $user->type === UserType::Admin
            ? route('admin.dashboard')
            : route('dashboard.index');

        $this->redirect($destination);
    }

    public function resendOtp(): void
    {
        $this->otp = '';
        $this->error = '';
        $this->sendOtp(isResend: true);
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
