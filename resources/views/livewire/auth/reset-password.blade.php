<x-layouts::auth :title="__('Reset Password')">
    <div class="space-y-8">
        <!-- Header -->
        <div class="text-center space-y-2">
            <h1 class="text-xl font-semibold text-base-content">{{ __('Reset password') }}</h1>
            <p class="text-[14px] text-base-content/50 font-medium leading-relaxed">
                {{ __('Enter your new password below.') }}
            </p>
        </div>

        <form method="POST" action="{{ route('password.update') }}" class="space-y-6">
            @csrf
            <!-- Token -->
            <input type="hidden" name="token" value="{{ request()->route('token') }}">

            <div class="space-y-5">
                <!-- Email Address -->
                <x-auth.input
                    name="email"
                    type="email"
                    :label="__('Email Address')"
                    value="{{ request('email') }}"
                    required
                    autocomplete="email"
                    placeholder="e.g. staff@dpcatering.com"
                >
                    <x-slot:icon>
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                    </x-slot:icon>
                </x-auth.input>

                <!-- Password -->
                <x-auth.input
                    name="password"
                    type="password"
                    :label="__('New Password')"
                    required
                    autocomplete="new-password"
                    placeholder="••••••••"
                >
                    <x-slot:icon>
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                    </x-slot:icon>
                </x-auth.input>

                <!-- Confirm Password -->
                <x-auth.input
                    name="password_confirmation"
                    type="password"
                    :label="__('Confirm Password')"
                    required
                    autocomplete="new-password"
                    placeholder="••••••••"
                >
                    <x-slot:icon>
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                    </x-slot:icon>
                </x-auth.input>
            </div>

            <x-auth.button type="submit">
                {{ __('Reset Password') }}
            </x-auth.button>
        </form>
    </div>
</x-layouts::auth>