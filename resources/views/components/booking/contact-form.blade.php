@props([
    'verifyPhone' => false,
    'otpStep' => 0,
    'otpError' => '',
    'phone' => '',
])

{{-- Contact form with OTP verification. Uses wire:model bindings from parent Livewire component. --}}
<div class="grid gap-6">
    <div class="space-y-2">
        <label class="text-[11px] font-bold uppercase tracking-widest text-base-content/60 ml-1">Your Full Name</label>
        <input type="text" wire:model="name" class="w-full px-5 py-4 bg-base-200 border border-base-content/10 focus:border-primary focus:ring-4 focus:ring-primary/20 rounded-xl transition-all text-[15px] font-medium placeholder:text-base-content/30" placeholder="e.g. Grace Ayensu">
        @error('name') <span class="text-xs font-bold text-error mt-1 block">{{ $message }}</span> @enderror
    </div>

    <div class="space-y-2">
        <label class="text-[11px] font-bold uppercase tracking-widest text-base-content/60 ml-1">Phone Number</label>
        <input type="tel" wire:model="phone" class="w-full px-5 py-4 bg-base-200 border border-base-content/10 focus:border-primary focus:ring-4 focus:ring-primary/20 rounded-xl transition-all text-[15px] font-medium placeholder:text-base-content/30" placeholder="024 XXX XXXX">
        @auth
            <p class="text-[11px] text-base-content/50 font-medium mt-1 ml-1">{{ __('This number will be used for payment.') }}</p>
        @endauth
        @error('phone') <span class="text-xs font-bold text-error mt-1 block">{{ $message }}</span> @enderror
    </div>

    <div class="space-y-2">
        <label class="text-[11px] font-bold uppercase tracking-widest text-base-content/60 ml-1">Email <span class="italic lowercase font-medium opacity-50">(Recommended)</span></label>
        <input type="email" wire:model="email" class="w-full px-5 py-4 bg-base-200 border border-base-content/10 focus:border-primary focus:ring-4 focus:ring-primary/20 rounded-xl transition-all text-[15px] font-medium placeholder:text-base-content/30" placeholder="grace@example.com">
        @error('email') <span class="text-xs font-bold text-error mt-1 block">{{ $message }}</span> @enderror
    </div>

    @guest
        <div class="mt-4 p-4 sm:p-6 bg-primary/5 border border-primary/10 rounded-2xl space-y-4">
            <label class="flex items-center gap-3 cursor-pointer group">
                <input type="checkbox" wire:model.live="verifyPhone" class="size-5 rounded border-base-content/10 text-primary focus:ring-primary/20 transition-all">
                <div>
                    <span class="text-[14px] font-bold text-base-content group-hover:text-primary transition-colors">
                        Verify phone to track your booking
                    </span>
                    <p class="text-[11px] text-base-content/40 mt-0.5">
                        We'll send a code to your phone number above so you can sign in and track your order.
                    </p>
                </div>
            </label>

            @if($verifyPhone && $otpStep === 0)
                <div class="pt-2">
                    <button type="button" wire:click="sendOtp" wire:loading.attr="disabled"
                        class="w-full h-12 bg-primary hover:bg-primary/90 text-white rounded-full font-bold text-[12px] uppercase tracking-widest transition-all hover:scale-[1.02] active:scale-95 flex items-center justify-center gap-2">
                        <span wire:loading.remove wire:target="sendOtp">Send Verification Code</span>
                        <span wire:loading wire:target="sendOtp" class="flex items-center gap-2">
                            <span class="loading loading-spinner loading-sm"></span>
                            Sending...
                        </span>
                    </button>
                    @error('phone') <p class="text-[11px] font-bold text-error mt-2">{{ $message }}</p> @enderror
                </div>
            @elseif($otpStep === 2)
                <div class="pt-2 space-y-4">
                    <p class="text-[13px] text-base-content/60 font-medium">
                        We've sent a 6-digit code to <strong class="text-base-content">{{ $phone }}</strong>.
                    </p>

                    @if($otpError)
                        <div class="p-3 bg-error/10 text-error text-[12px] font-bold rounded-xl border border-error/10 flex items-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="size-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            {{ $otpError }}
                        </div>
                    @endif

                    <x-auth.otp-grid wireModel="otp" wireSubmit="verifyOtp" wireResend="resendOtp" :compact="true" />

                    <x-auth.resend-timer wireResend="resendOtp" :seconds="60" />

                    <div class="flex gap-3">
                        <button type="button" wire:click="cancelOtp"
                            class="flex-1 h-11 text-[12px] font-bold text-base-content/50 hover:text-base-content border border-base-content/10 rounded-full transition-colors">
                            Cancel
                        </button>
                        <button type="button" wire:click="verifyOtp" wire:loading.attr="disabled"
                            class="flex-1 h-11 bg-success text-white rounded-full font-bold text-[12px] uppercase tracking-widest transition-all hover:scale-[1.02] active:scale-95 flex items-center justify-center gap-2">
                            <span wire:loading.remove wire:target="verifyOtp">Verify</span>
                            <span wire:loading wire:target="verifyOtp" class="flex items-center gap-2">
                                <span class="loading loading-spinner loading-sm"></span>
                                Verifying...
                            </span>
                        </button>
                    </div>
                </div>
            @endif
        </div>
    @endguest
</div>
