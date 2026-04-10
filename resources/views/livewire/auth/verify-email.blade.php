<x-layouts::auth :title="__('Verify Email')">
    <div class="space-y-8">
        <!-- Header -->
        <div class="text-center space-y-2">
            <h1 class="text-xl font-semibold text-base-content">{{ __('Verify your email') }}</h1>
            <p class="text-[14px] text-base-content/50 font-medium leading-relaxed">
                {{ __('Please verify your email address by clicking the link we sent to you.') }}
            </p>
        </div>

        @if (session('status') == 'verification-link-sent')
            <div class="p-4 bg-success/10 border border-success/15 rounded-lg flex items-center gap-3">
                <div class="size-8 bg-success/10 rounded-full flex items-center justify-center shrink-0">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-success" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                </div>
                <p class="text-[13px] font-medium text-success leading-relaxed">
                    {{ __('A new verification link has been sent to the email address you provided during registration.') }}
                </p>
            </div>
        @endif

        <div class="space-y-4">
            <form method="POST" action="{{ route('verification.send') }}">
                @csrf
                <x-app.button type="submit" class="w-full">
                    {{ __('Resend Verification Email') }}
                </x-app.button>
            </form>

            <form method="POST" action="{{ route('logout') }}" class="text-center">
                @csrf
                <button type="submit" class="text-[13px] text-base-content/40 font-medium hover:text-primary transition-colors">
                    {{ __('Log out') }}
                </button>
            </form>
        </div>
    </div>
</x-layouts::auth>