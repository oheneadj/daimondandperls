<x-layouts::auth :title="__('Recover Access')">
    <div class="space-y-12">
        <!-- Header -->
        <div class="text-center space-y-2">
            <h1 class=" text-4xl font-semibold tracking-tight text-base-content">
                {{ __('Recover Access') }}
            </h1>
            <p class=" text-[14px] text-base-content/60 font-medium max-w-[300px] mx-auto leading-relaxed italic">
                {{ __('Enter your email address and we will dispatch a secure link to reset your access credentials.') }}
            </p>
        </div>

        <!-- Session Status -->
        <x-auth-session-status :status="session('status')" />

        <form method="POST" action="{{ route('password.email') }}" class="space-y-8">
            @csrf

            <!-- Email Address -->
            <div class="space-y-2.5">
                <label class="text-[11px] font-bold uppercase tracking-widest text-base-content/60 ml-0.5">{{ __('Email Address') }}</label>
                <x-ui.input name="email" type="email" value="{{ old('email') }}" required autofocus placeholder="e.g. staff@dpcatering.com" class="rounded-xl h-14 bg-base-100 border-base-content/10/60 focus:ring-4 focus:ring-primary/20 shadow-sm" />
                @error('email') <p class="text-[12px] font-bold text-error mt-1.5 ml-1 tracking-tight">{{ $message }}</p> @enderror
            </div>

            <div class="pt-4">
                <x-ui.button type="submit" variant="primary" size="lg" class="w-full h-14 rounded-xl shadow-md text-[14px] uppercase tracking-widest">
                    {{ __('Dispatch Recovery Link') }}
                </x-ui.button>
            </div>
        </form>

        <div class="text-center pt-8 border-t border-dp-pearl-mid">
            <p class="text-[13px] text-base-content/60 font-medium">
                {{ __('Remembered your password?') }}
                <a href="{{ route('login') }}" class="text-primary font-bold hover:text-primary-dark transition-colors ml-1" wire:navigate>
                    {{ __('Sign In') }}
                </a>
            </p>
        </div>
    </div>
</x-layouts::auth>