<?php

use Livewire\Component;
use App\Models\User;
use App\Notifications\OtpNotification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

new class extends Component
{
    public string $phone = '';
    public string $otp = '';
    public int $step = 1; // 1: Enter Phone, 2: Enter OTP
    public string $error = '';

    public function sendOtp()
    {
        $this->validate([
            'phone' => 'required|string|min:10',
        ]);

        $user = User::where('phone', $this->phone)->first();

        if (!$user) {
            // Check if there is a customer with this phone
            $customer = \App\Models\Customer::where('phone', $this->phone)->first();
            
            // Auto-register
            $user = User::create([
                'name' => $customer?->name ?? 'Customer ' . substr($this->phone, -4),
                'email' => $customer?->email ?? $this->phone . '@dpc.test',
                'phone' => $this->phone,
                'password' => Hash::make(Str::random(32)),
                'role' => \App\Enums\UserRole::Staff, // Use Staff or a default role if Customer doesn't exist
            ]);

            if ($customer) {
                $customer->update(['user_id' => $user->id]);
            }
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

    public function verifyOtp()
    {
        $this->validate([
            'otp' => 'required|string|size:6',
        ]);

        $user = User::where('phone', $this->phone)
            ->where('otp_code', $this->otp)
            ->where('otp_expires_at', '>', now())
            ->first();

        if (!$user) {
            $this->error = 'Invalid or expired OTP code.';
            return;
        }

        // Clear OTP
        $user->update([
            'otp_code' => null,
            'otp_expires_at' => null,
        ]);

        Auth::login($user, true);

        return redirect()->intended(route('dashboard'));
    }

    public function backToPhone()
    {
        $this->step = 1;
        $this->otp = '';
        $this->error = '';
    }
};
?>

<div class="bg-white p-8 rounded-3xl shadow-xl border border-base-content/10">
    <div class="text-center mb-8">
        <h2 class=" text-2xl font-bold text-base-content">{{ $step === 1 ? 'Login with Phone' : 'Verify Code' }}</h2>
        <p class="text-[14px] text-base-content/60 mt-2 font-medium">
            {{ $step === 1 ? 'Enter your phone number to receive a secure login code.' : "We've sent a 6-digit code to {$phone}." }}
        </p>
    </div>

    @if($error)
        <div class="mb-6 p-4 bg-primary-soft text-primary text-[13px] font-bold rounded-xl border border-dp-rose/10 flex items-center gap-3">
            <svg xmlns="http://www.w3.org/2000/svg" class="size-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            {{ $error }}
        </div>
    @endif

    <div class="space-y-6">
        @if($step === 1)
            <!-- Step 1: Phone -->
            <div>
                <label for="phone" class="block text-[11px] font-black uppercase tracking-widest text-base-content/60 mb-2 ml-1">Phone Number</label>
                <div class="relative group">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-base-content/60 group-focus-within:text-primary transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" class="size-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                        </svg>
                    </div>
                    <input wire:model="phone" type="tel" id="phone" placeholder="e.g., 0244000000" class="block w-full pl-12 pr-4 py-4 bg-base-200 border-base-content/10 rounded-2xl text-[15px] font-medium focus:ring-4 focus:ring-dp-rose/10 focus:border-dp-rose transition-all placeholder:text-base-content/60/40" required>
                </div>
                @error('phone') <span class="text-xs text-primary font-bold mt-1 ml-1 block">{{ $message }}</span> @enderror
            </div>

            <button wire:click="sendOtp" wire:loading.attr="disabled" class="w-full bg-dp-text-primary text-white font-bold py-4 rounded-2xl shadow-md hover:bg-black transition-all flex items-center justify-center gap-3 group">
                <span wire:loading.remove>Send Verification Code</span>
                <span wire:loading>Sending...</span>
                <svg wire:loading.remove xmlns="http://www.w3.org/2000/svg" class="size-5 group-hover:translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                </svg>
            </button>
        @else
            <!-- Step 2: OTP -->
            <div>
                <label for="otp" class="block text-[11px] font-black uppercase tracking-widest text-base-content/60 mb-2 ml-1">6-Digit Code</label>
                <input wire:model="otp" type="text" id="otp" maxlength="6" placeholder="000000" class="block w-full px-4 py-4 bg-base-200 border-base-content/10 rounded-2xl text-center text-2xl font-black tracking-[0.5em] focus:ring-4 focus:ring-primary/20 focus:border-dp-rose transition-all placeholder:text-base-content/60/20" required autofocus>
                @error('otp') <span class="text-xs text-primary font-bold mt-1 ml-1 block">{{ $message }}</span> @enderror
            </div>

            <div class="space-y-4">
                <button wire:click="verifyOtp" wire:loading.attr="disabled" class="w-full bg-primary text-white font-bold py-4 rounded-2xl shadow-md hover:bg-primary-hover transition-all flex items-center justify-center gap-3">
                    <span wire:loading.remove>Verify & Login</span>
                    <span wire:loading>Verifying...</span>
                </button>
                
                <button wire:click="backToPhone" class="w-full text-[13px] font-bold text-base-content/60 hover:text-base-content transition-colors flex items-center justify-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Use a different number
                </button>
            </div>
        @endif
    </div>
</div>