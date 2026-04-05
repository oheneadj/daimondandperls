<x-layouts::auth :title="__('Forgot Password')">
    <div class="space-y-8">
        <!-- Header -->
        <div class="text-center space-y-2">
            <h1 class="text-xl font-semibold text-base-content">{{ __('Forgot password?') }}</h1>
            <p class="text-[14px] text-base-content/50 font-medium leading-relaxed">
                {{ __("Enter your email and we'll send you a reset link.") }}
            </p>
        </div>

        <!-- Session Status -->
        <x-auth-session-status :status="session('status')" />

        <form method="POST" action="{{ route('password.email') }}" class="space-y-6">
            @csrf

            <!-- Email Address -->
            <x-auth.input
                name="email"
                type="email"
                :label="__('Email Address')"
                value="{{ old('email') }}"
                required
                autofocus
                placeholder="e.g. staff@dpcatering.com"
            >
                <x-slot:icon>
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                </x-slot:icon>
            </x-auth.input>

            <x-auth.button type="submit">
                {{ __('Send Reset Link') }}
            </x-auth.button>
        </form>

        <div class="text-center pt-6 border-t border-base-content/5">
            <p class="text-[13px] text-base-content/40 font-medium">
                {{ __('Remembered your password?') }}
                <a href="{{ route('login') }}" class="text-primary font-semibold hover:text-primary/80 transition-colors ml-1" wire:navigate>
                    {{ __('Sign in') }}
                </a>
            </p>
        </div>
    </div>
</x-layouts::auth>