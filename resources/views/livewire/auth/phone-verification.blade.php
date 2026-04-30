<div>
    <div class="text-center mb-6">
        <div class="size-14 rounded-full bg-primary/10 flex items-center justify-center mx-auto mb-4">
            <svg xmlns="http://www.w3.org/2000/svg" class="size-7 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
            </svg>
        </div>
        <h2 class="text-xl font-semibold text-base-content">Verify Your Phone</h2>
        <p class="text-[14px] text-base-content/50 mt-2 font-medium leading-relaxed">
            We've sent a 6-digit code to <span class="text-base-content font-semibold">{{ Auth::user()->phone }}</span>.
        </p>
    </div>

    @if($error)
        <div class="mb-5 p-4 bg-error/10 text-error text-[13px] font-medium rounded-lg border border-error/15 flex items-center gap-3">
            <svg xmlns="http://www.w3.org/2000/svg" class="size-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            {{ $error }}
        </div>
    @endif

    <div class="space-y-5">
        <x-auth.otp-grid wireModel="code" wireSubmit="verify" wireResend="resend" />

        <x-auth.resend-timer wireResend="resend" :seconds="60" />

        <x-app.button type="button" class="w-full" variant="primary" wireClick="verify" wireTarget="verify" loadingText="Verifying...">
            Verify Phone Number
        </x-app.button>

        <a href="{{ route('dashboard.index') }}" class="block text-center text-[12px] text-base-content/40 hover:text-base-content/60 font-medium transition-colors">
            I'll do this later
        </a>
    </div>
</div>
