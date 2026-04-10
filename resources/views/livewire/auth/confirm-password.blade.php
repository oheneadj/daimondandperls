<x-layouts::auth :title="__('Confirm Password')">
    <div class="space-y-8">
        <!-- Header -->
        <div class="text-center space-y-2">
            <h1 class="text-xl font-semibold text-base-content">{{ __('Confirm your password') }}</h1>
            <p class="text-[14px] text-base-content/50 font-medium leading-relaxed">
                {{ __('This is a secure area. Please confirm your password before continuing.') }}
            </p>
        </div>

        <x-auth-session-status :status="session('status')" />

        <form method="POST" action="{{ route('password.confirm.store') }}" class="space-y-6">
            @csrf

            <!-- Password -->
            <x-app.input
                name="password"
                type="password"
                :label="__('Password')"
                required
                autocomplete="current-password"
                placeholder="••••••••"
            >
                <x-slot:icon>
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                </x-slot:icon>
            </x-app.input>

            <x-app.button type="submit" class="w-full">
                {{ __('Confirm') }}
            </x-app.button>
        </form>
    </div>
</x-layouts::auth>