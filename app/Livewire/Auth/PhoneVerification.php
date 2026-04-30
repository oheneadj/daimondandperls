<?php

declare(strict_types=1);

namespace App\Livewire\Auth;

use App\Notifications\PhoneOtpNotification;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.auth.simple')]
class PhoneVerification extends Component
{
    public string $code = '';

    public string $error = '';

    public bool $codeSent = false;

    public function mount(): void
    {
        if (Auth::user()->hasVerifiedPhone()) {
            $this->redirect(route('dashboard.index'));

            return;
        }

        $this->sendCode();
    }

    public function sendCode(bool $isResend = false): void
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $otp = (string) random_int(100000, 999999);

        Cache::put("phone_otp_{$user->id}", $otp, now()->addMinutes(10));

        $user->notify(new PhoneOtpNotification($otp, $isResend));

        $this->codeSent = true;
        $this->error = '';
    }

    public function verify(): void
    {
        $this->validate([
            'code' => ['required', 'string', 'size:6'],
        ], [
            'code.required' => 'Please enter the 6-digit code.',
            'code.size' => 'The code must be exactly 6 digits.',
        ]);

        /** @var \App\Models\User $user */
        $user = Auth::user();
        $cached = Cache::get("phone_otp_{$user->id}");

        if (! $cached || $cached !== $this->code) {
            $this->error = 'Invalid or expired verification code.';

            return;
        }

        Cache::forget("phone_otp_{$user->id}");

        $user->update(['phone_verified_at' => now()]);

        $this->redirect(route('dashboard.index'));
    }

    public function resend(): void
    {
        $this->code = '';
        $this->sendCode(isResend: true);
    }

    public function render(): View
    {
        return view('livewire.auth.phone-verification');
    }
}
