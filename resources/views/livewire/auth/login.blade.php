<x-layouts::auth :title="__('Login')">
    <div class="space-y-10">
        <!-- Header -->
        <div class="text-center space-y-3">
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-primary/10 text-primary text-[10px] font-black uppercase tracking-widest mb-2">
                <span class="w-1.5 h-1.5 rounded-full bg-primary animate-pulse"></span>
                {{ __('Secure Access') }}
            </div>
            <h1 class="text-4xl font-black tracking-tight text-base-content leading-tight">
                {{ __('Welcome back') }}
            </h1>
            <p class="text-[14px] text-base-content/50 font-medium max-w-[280px] mx-auto leading-relaxed italic">
                {{ __('Manage your exquisite catering experiences with Diamonds & Pearls.') }}
            </p>
        </div>

        <div x-data="{ loginMode: 'email' }" class="space-y-8">
            <!-- Toggle / Tab Switcher -->
            <div class="flex p-1.5 bg-[#F4F4F6] rounded-[20px] border border-base-content/5">
                <button 
                    @click="loginMode = 'email'" 
                    :class="loginMode === 'email' ? 'bg-white shadow-lg shadow-black/5 text-[#121212]' : 'text-base-content/40 hover:text-base-content/60'" 
                    class="flex-1 py-3 text-[11px] font-black uppercase tracking-widest rounded-[14px] transition-all duration-300"
                >
                    Email & Pass
                </button>
                <button 
                    @click="loginMode = 'phone'" 
                    :class="loginMode === 'phone' ? 'bg-white shadow-lg shadow-black/5 text-[#121212]' : 'text-base-content/40 hover:text-base-content/60'" 
                    class="flex-1 py-3 text-[11px] font-black uppercase tracking-widest rounded-[14px] transition-all duration-300"
                >
                    Phone (OTP)
                </button>
            </div>

            <!-- Email Login -->
            <div x-show="loginMode === 'email'" x-cloak x-transition:enter="transition duration-400 ease-out" x-transition:enter-start="opacity-0 translate-y-4 scale-95" x-transition:enter-end="opacity-100 translate-y-0 scale-100">
                <x-auth-session-status :status="session('status')" />

                <form method="POST" action="{{ route('login.store') }}" class="space-y-7">
                    @csrf
                    <div class="space-y-6">
                        <!-- Email -->
                        <div class="space-y-2.5">
                            <label class="text-[10px] font-black uppercase tracking-[0.2em] text-base-content/40 ml-1">{{ __('Email Address') }}</label>
                            <div class="relative group">
                                <span class="absolute inset-y-0 left-5 flex items-center text-base-content/20 group-focus-within:text-primary transition-colors">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                                </span>
                                <x-ui.input name="email" type="email" value="{{ old('email') }}" required autofocus placeholder="e.g. admin@dpcatering.com" class="pl-14 rounded-[18px] h-14 bg-[#F4F4F6]/50 border-transparent focus:bg-white focus:ring-4 focus:ring-primary/10 shadow-inner group-focus-within:shadow-none transition-all" />
                            </div>
                            @error('email') <p class="text-[11px] font-bold text-error mt-2 ml-1">{{ $message }}</p> @enderror
                        </div>

                        <!-- Password -->
                        <div class="space-y-2.5">
                            <div class="flex justify-between items-center px-1">
                                <label class="text-[10px] font-black uppercase tracking-[0.2em] text-base-content/40">{{ __('Access Password') }}</label>
                                @if (Route::has('password.request'))
                                    <a class="text-[10px] font-black text-primary uppercase tracking-[0.2em] hover:text-primary-dark transition-all" href="{{ route('password.request') }}" wire:navigate>{{ __('Forgotten?') }}</a>
                                @endif
                            </div>
                            <div class="relative group">
                                <span class="absolute inset-y-0 left-5 flex items-center text-base-content/20 group-focus-within:text-primary transition-colors">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 00-2 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                                </span>
                                <x-ui.input name="password" type="password" required placeholder="••••••••" class="pl-14 rounded-[18px] h-14 bg-[#F4F4F6]/50 border-transparent focus:bg-white focus:ring-4 focus:ring-primary/10 shadow-inner group-focus-within:shadow-none transition-all" />
                            </div>
                            @error('password') <p class="text-[11px] font-bold text-error mt-2 ml-1">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="flex items-center justify-between px-1">
                        <label class="relative inline-flex items-center cursor-pointer group">
                            <input type="checkbox" name="remember" class="sr-only peer">
                            <div class="w-9 h-5 bg-base-content/10 rounded-full peer peer-checked:after:translate-x-full after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-primary shadow-sm"></div>
                            <span class="ml-3 text-[12px] text-base-content/50 font-bold group-hover:text-base-content transition-colors">{{ __('Stay Connected') }}</span>
                        </label>
                    </div>

                    <div class="pt-2">
                        <button type="submit" class="w-full h-15 bg-[#121212] hover:bg-black text-white rounded-[20px] shadow-xl shadow-black/10 font-black text-[13px] uppercase tracking-[0.2em] transition-all hover:scale-[1.02] active:scale-95 flex items-center justify-center gap-3 group">
                            {{ __('Authenticate') }}
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 translate-x-0 group-hover:translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                            </svg>
                        </button>
                    </div>
                </form>
            </div>

            <!-- Phone OTP Login -->
            <div x-show="loginMode === 'phone'" x-cloak x-transition:enter="transition duration-400 ease-out" x-transition:enter-start="opacity-0 translate-y-4 scale-95" x-transition:enter-end="opacity-100 translate-y-0 scale-100">
                <livewire:auth.otp-login />
            </div>
        </div>

        @if (Route::has('register'))
            <div class="text-center pt-8 border-t border-base-content/5">
                <p class="text-[12px] text-base-content/40 font-medium tracking-tight">
                    {{ __('New team member?') }}
                    <a href="{{ route('register') }}" class="text-primary font-black uppercase tracking-widest ml-1.5 hover:text-primary-dark transition-colors" wire:navigate>
                        {{ __('Apply for Access') }}
                    </a>
                </p>
            </div>
        @endif
    </div>
</x-layouts::auth>
x-layouts::auth>