<div>
    <div class="text-center mb-8">
        <h2 class="text-2xl font-bold text-base-content">{{ $step === 1 ? 'Login with Phone' : 'Verify Code' }}</h2>
        <p class="text-[14px] text-base-content/60 mt-2 font-medium">
            {{ $step === 1 ? 'Enter your phone number to receive a secure login code.' : "We've sent a 6-digit code to {$phone}." }}
        </p>
    </div>

    @if($error)
        <div class="mb-6 p-4 bg-error/10 text-error text-[13px] font-bold rounded-xl border border-error/10 flex items-center gap-3">
            <svg xmlns="http://www.w3.org/2000/svg" class="size-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            {{ $error }}
        </div>
    @endif

    <div class="space-y-6">
        @if($step === 1)
            <!-- Step 1: Phone -->
            <x-auth.input
                name="phone"
                type="tel"
                :label="__('Phone Number')"
                wireModel="phone"
                required
                placeholder="e.g., 0244000000"
            >
                <x-slot:icon>
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                    </svg>
                </x-slot:icon>
            </x-auth.input>

            <x-auth.button type="button" wireClick="sendOtp" wireTarget="sendOtp" loadingText="Sending...">
                Send Verification Code
            </x-auth.button>
        @else
            <!-- Step 2: OTP Grid -->
            <x-auth.otp-grid wireModel="otp" wireSubmit="verifyOtp" wireResend="resendOtp" />

            <x-auth.resend-timer wireResend="resendOtp" :seconds="60" />

            <div class="space-y-4">
                <x-auth.button type="button" variant="primary" wireClick="verifyOtp" wireTarget="verifyOtp" loadingText="Verifying...">
                    Verify & Login
                </x-auth.button>

                <x-auth.button type="button" variant="ghost" wireClick="backToPhone">
                    <svg xmlns="http://www.w3.org/2000/svg" class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Use a different number
                </x-auth.button>
            </div>
        @endif
    </div>
</div>
