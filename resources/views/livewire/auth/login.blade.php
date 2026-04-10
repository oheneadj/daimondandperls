<x-layouts::auth :title="__('Login')" :maxWidth="'max-w-[460px]'">
    <div class="space-y-8">
        <!-- Header -->
        <div class="text-center space-y-2">
            <h1 class="text-xl font-semibold text-base-content">{{ __('Welcome back') }}</h1>
            <p class="text-[14px] text-base-content/50 font-medium leading-relaxed">
                {{ __('Sign in to your account to continue.') }}
            </p>
        </div>

        <div x-data="{ loginMode: 'email' }" class="space-y-6">
            <!-- Toggle / Tab Switcher -->
            <div class="flex p-1 bg-base-200 rounded-lg border border-base-content/5">
                <button
                    @click="loginMode = 'email'"
                    :class="loginMode === 'email' ? 'bg-base-100 shadow-sm text-base-content border border-base-content/10' : 'text-base-content/40 hover:text-base-content/60 border border-transparent'"
                    class="flex-1 py-2.5 text-[13px] font-medium rounded-md transition-all duration-200"
                >
                    Email & Password
                </button>
                <button
                    @click="loginMode = 'phone'"
                    :class="loginMode === 'phone' ? 'bg-base-100 shadow-sm text-base-content border border-base-content/10' : 'text-base-content/40 hover:text-base-content/60 border border-transparent'"
                    class="flex-1 py-2.5 text-[13px] font-medium rounded-md transition-all duration-200"
                >
                    Phone (OTP)
                </button>
            </div>

            <!-- Email Login -->
            <div x-show="loginMode === 'email'" x-cloak x-transition:enter="transition duration-300 ease-out" x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0">
                <x-auth-session-status :status="session('status')" />

                <form method="POST" action="{{ route('login.store') }}" class="space-y-6">
                    @csrf
                    <div class="space-y-5">
                        <!-- Email -->
                        <x-app.input
                            name="email"
                            type="email"
                            :label="__('Email Address')"
                            value="{{ old('email') }}"
                            required
                            autofocus
                            placeholder="e.g. admin@dpcatering.com"
                        >
                            <x-slot:icon>
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                            </x-slot:icon>
                        </x-app.input>

                        <!-- Password -->
                        <x-app.input
                            name="password"
                            type="password"
                            :label="__('Password')"
                            required
                            placeholder="••••••••"
                        >
                            <x-slot:icon>
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                            </x-slot:icon>
                        </x-app.input>
                    </div>

                    <!-- Actions -->
                    <div class="flex items-center justify-between">
                        <label class="flex items-center gap-2 cursor-pointer group">
                            <input type="checkbox" name="remember" class="checkbox checkbox-sm checkbox-primary rounded">
                            <span class="text-[13px] text-base-content/50 font-medium group-hover:text-base-content transition-colors">{{ __('Remember me') }}</span>
                        </label>
                        @if (Route::has('password.request'))
                            <a class="text-[13px] font-medium text-primary hover:text-primary/80 transition-colors" href="{{ route('password.request') }}" wire:navigate>{{ __('Forgot password?') }}</a>
                        @endif
                    </div>

                    <x-app.button type="submit" class="w-full">
                        {{ __('Sign In') }}
                    </x-app.button>
                </form>
            </div>

            <!-- Phone OTP Login -->
            <div x-show="loginMode === 'phone'" x-cloak x-transition:enter="transition duration-300 ease-out" x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0">
                <livewire:auth.otp-login />
            </div>
        </div>

        @if (Route::has('register'))
            <div class="text-center pt-6 border-t border-base-content/5">
                <p class="text-[13px] text-base-content/40 font-medium">
                    {{ __("Don't have an account?") }}
                    <a href="{{ route('register') }}" class="text-primary font-semibold hover:text-primary/80 transition-colors ml-1" wire:navigate>
                        {{ __('Sign up') }}
                    </a>
                </p>
            </div>
        @endif
    </div>
</x-layouts::auth>
