<x-layouts::auth :title="__('Login')">
    <div class="space-y-12">
        <!-- Header -->
        <div class="text-center space-y-2">
            <h1 class=" text-3xl sm:text-4xl font-semibold tracking-tight text-base-content">
                {{ __('Diamonds & Pearls') }}
            </h1>
            <p class=" text-[14px] text-base-content/60 font-medium max-w-[300px] mx-auto leading-relaxed italic">
                {{ __('Access your dashboard to track your exquisite catering experiences.') }}
            </p>
        </div>

        <div x-data="{ loginMode: 'email' }" class="space-y-8">
            <!-- Toggle -->
            <div class="flex p-1.5 bg-base-200 rounded-2xl border border-base-content/10/60">
                <button @click="loginMode = 'email'" :class="loginMode === 'email' ? 'bg-white shadow-sm text-base-content' : 'text-base-content/60 hover:text-base-content'" class="flex-1 py-3 text-[12px] font-black uppercase tracking-widest rounded-xl transition-all">
                    Email & Password
                </button>
                <button @click="loginMode = 'phone'" :class="loginMode === 'phone' ? 'bg-white shadow-sm text-base-content' : 'text-base-content/60 hover:text-base-content'" class="flex-1 py-3 text-[12px] font-black uppercase tracking-widest rounded-xl transition-all">
                    Phone Number (OTP)
                </button>
            </div>

            <!-- Email Login -->
            <div x-show="loginMode === 'email'" x-cloak x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0">
                <!-- Session Status -->
                <x-auth-session-status :status="session('status')" />

                <form method="POST" action="{{ route('login.store') }}" class="space-y-8">
                    @csrf
                    <!-- Credentials Group -->
                    <div class="space-y-6">
                        <!-- Email Address -->
                        <div class="space-y-2.5">
                            <label class="text-[11px] font-bold uppercase tracking-widest text-base-content/60 ml-0.5">{{ __('Email Address') }}</label>
                            <div class="relative group">
                                <span class="absolute inset-y-0 left-5 flex items-center text-dp-text-disabled/40 group-focus-within:text-primary transition-colors">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="size-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                    </svg>
                                </span>
                                <x-ui.input name="email" type="email" value="{{ old('email') }}" required autofocus placeholder="e.g. admin@dpcatering.com" class="pl-14 rounded-xl h-14 bg-base-100 border-base-content/10/60 focus:ring-4 focus:ring-primary/20 shadow-sm" />
                            </div>
                            @error('email') <p class="text-[12px] font-bold text-error mt-1.5 ml-1 tracking-tight">{{ $message }}</p> @enderror
                        </div>

                        <!-- Password -->
                        <div class="space-y-2.5">
                            <div class="flex justify-between items-center ml-0.5">
                                <label class="text-[11px] font-bold uppercase tracking-widest text-base-content/60">{{ __('Access Password') }}</label>
                                @if (Route::has('password.request'))
                                    <a class="text-[11px] font-bold text-primary uppercase tracking-widest hover:text-primary-dark transition-colors" href="{{ route('password.request') }}" wire:navigate>
                                        {{ __('Forgotten?') }}
                                    </a>
                                @endif
                            </div>
                            <div class="relative group">
                                <span class="absolute inset-y-0 left-5 flex items-center text-dp-text-disabled/40 group-focus-within:text-primary transition-colors">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="size-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 00-2 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                    </svg>
                                </span>
                                <x-ui.input name="password" type="password" required placeholder="••••••••" class="pl-14 rounded-xl h-14 bg-base-100 border-base-content/10/60 focus:ring-4 focus:ring-primary/20 shadow-sm" />
                            </div>
                            @error('password') <p class="text-[12px] font-bold text-error mt-1.5 ml-1 tracking-tight">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <!-- Remember Me -->
                    <div class="flex items-center ml-1">
                        <label class="relative inline-flex items-center cursor-pointer group">
                            <input type="checkbox" name="remember" class="sr-only peer">
                            <div class="w-10 h-5 border-base-content/10 rounded-full peer peer-checked:after:translate-x-full after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-base-100 after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-primary shadow-sm"></div>
                            <span class="ml-3 text-[13px] text-base-content/60 font-bold group-hover:text-base-content transition-colors">{{ __('Stay Connected') }}</span>
                        </label>
                    </div>

                    <!-- Action -->
                    <div class="pt-2">
                        <x-ui.button type="submit" variant="primary" size="lg" class="w-full h-14 rounded-xl shadow-md text-[14px] uppercase tracking-widest">
                            {{ __('Authenticate') }}
                        </x-ui.button>
                    </div>
                </form>
            </div>

            <!-- Phone OTP Login -->
            <div x-show="loginMode === 'phone'" x-cloak x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0">
                <livewire:auth.otp-login />
            </div>
        </div>

        @if (Route::has('register'))
            <div class="text-center pt-8 border-t border-dp-pearl-mid">
                <p class="text-[13px] text-base-content/60 font-medium">
                    {{ __('New team member?') }}
                    <a href="{{ route('register') }}" class="text-primary font-bold hover:text-primary-dark transition-colors ml-1" wire:navigate>
                        {{ __('Apply for Access') }}
                    </a>
                </p>
            </div>
        @endif
    </div>
</x-layouts::auth>