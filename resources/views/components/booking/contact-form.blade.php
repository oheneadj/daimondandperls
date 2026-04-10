@props([
    'verifyPhone' => false,
    'otpStep' => 0,
    'otpError' => '',
    'phone' => '',
])

{{-- Contact form with OTP verification. Uses wire:model bindings from parent Livewire component. --}}
<div class="grid gap-5">
    <x-app.input
        name="name"
        type="text"
        :label="__('Your Full Name')"
        wire:model="name"
        placeholder="e.g. Grace Ayensu"
    >
        <x-slot:icon>
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
        </x-slot:icon>
    </x-app.input>

    <div class="space-y-1.5">
        <x-app.input
            name="phone"
            type="tel"
            :label="__('Phone Number')"
            wire:model="phone"
            placeholder="024 XXX XXXX"
        >
            <x-slot:icon>
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
            </x-slot:icon>
        </x-app.input>
        @auth
            <p class="text-xs text-base-content/60 ml-1">{{ __('This number will be used for payment.') }}</p>
        @endauth
    </div>

    <x-app.input
        name="email"
        type="email"
        :label="__('Email')"
        :hint="__('Recommended')"
        wire:model="email"
        placeholder="grace@example.com"
    >
        <x-slot:icon>
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
        </x-slot:icon>
    </x-app.input>

    @guest
        <div class="mt-2 p-4 sm:p-5 bg-primary/5 border border-primary/10 rounded-lg space-y-4">
            <label class="flex items-center gap-3 cursor-pointer group">
                <input type="checkbox" wire:model.live="verifyPhone" class="checkbox checkbox-sm checkbox-primary rounded">
                <div>
                    <span class="text-[14px] font-semibold text-base-content group-hover:text-primary transition-colors">
                        Get order updates & track your booking
                    </span>
                    <p class="text-xs text-base-content/40 mt-0.5">
                        We'll send your confirmation and status updates to this number.
                    </p>
                </div>
            </label>

            @if($verifyPhone && $otpStep === 0)
                <div class="pt-2">
                    <x-app.button type="button" variant="primary" size="md" wireClick="sendOtp" wireTarget="sendOtp" loadingText="Sending..." class="w-full">
                        Send Verification Code
                    </x-app.button>
                    @error('phone') <p class="text-xs text-error flex items-center gap-1 mt-2"><span>⚠</span> {{ $message }}</p> @enderror
                </div>
            @elseif($otpStep === 2)
                <div class="pt-2 space-y-4">
                    <p class="text-[13px] text-base-content/60 font-medium">
                        We've sent a 6-digit code to <strong class="text-base-content">{{ $phone }}</strong>.
                    </p>

                    @if($otpError)
                        <div class="p-3 bg-error/10 text-error text-[13px] font-medium rounded-lg border border-error/15 flex items-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="size-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            {{ $otpError }}
                        </div>
                    @endif

                    <x-auth.otp-grid wireModel="otp" wireSubmit="verifyOtp" wireResend="resendOtp" :compact="true" />

                    <x-auth.resend-timer wireResend="resendOtp" :seconds="60" />

                    <div class="flex gap-3">
                        <x-app.button type="button" variant="outline" size="md" wireClick="cancelOtp" class="flex-1">
                            Cancel
                        </x-app.button>
                        <x-app.button type="button" variant="green" size="md" wireClick="verifyOtp" wireTarget="verifyOtp" loadingText="Verifying..." class="flex-1">
                            Verify
                        </x-app.button>
                    </div>
                </div>
            @endif
        </div>
    @endguest
</div>
