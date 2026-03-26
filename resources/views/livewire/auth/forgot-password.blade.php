<x-layouts::auth :title="__('Recover Access')">
    <div class="space-y-10">
        <!-- Header -->
        <div class="text-center space-y-3">
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-primary/10 text-primary text-[10px] font-black uppercase tracking-widest mb-2">
                <span class="w-1.5 h-1.5 rounded-full bg-primary animate-pulse"></span>
                {{ __('Security Protocol') }}
            </div>
            <h1 class="text-4xl font-black tracking-tight text-base-content leading-tight">
                {{ __('Recover Access') }}
            </h1>
            <p class="text-[14px] text-base-content/50 font-medium max-w-[280px] mx-auto leading-relaxed italic">
                {{ __('Dispatch a secure link to reset your access credentials.') }}
            </p>
        </div>

        <!-- Session Status -->
        <x-auth-session-status :status="session('status')" />

        <form method="POST" action="{{ route('password.email') }}" class="space-y-8">
            @csrf

            <!-- Email Address -->
            <div class="space-y-2.5">
                <label class="text-[10px] font-black uppercase tracking-[0.2em] text-base-content/40 ml-1">{{ __('Email Address') }}</label>
                <div class="relative group">
                    <span class="absolute inset-y-0 left-5 flex items-center text-base-content/20 group-focus-within:text-primary transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                    </span>
                    <x-ui.input name="email" type="email" value="{{ old('email') }}" required autofocus placeholder="e.g. staff@dpcatering.com" class="pl-14 rounded-[18px] h-14 bg-[#F4F4F6]/50 border-transparent focus:bg-white focus:ring-4 focus:ring-primary/10 shadow-inner transition-all" />
                </div>
                @error('email') <p class="text-[11px] font-bold text-error mt-2 ml-1">{{ $message }}</p> @enderror
            </div>

            <div class="pt-4">
                <button type="submit" class="w-full h-15 bg-[#121212] hover:bg-black text-white rounded-[20px] shadow-xl shadow-black/10 font-black text-[13px] uppercase tracking-[0.2em] transition-all hover:scale-[1.02] active:scale-95 flex items-center justify-center gap-3 group">
                    {{ __('Dispatch Recovery Link') }}
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 translate-x-0 group-hover:translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                    </svg>
                </button>
            </div>
        </form>

        <div class="text-center pt-8 border-t border-base-content/5">
            <p class="text-[12px] text-base-content/40 font-medium tracking-tight">
                {{ __('Remembered your password?') }}
                <a href="{{ route('login') }}" class="text-primary font-black uppercase tracking-widest ml-1.5 hover:text-primary-dark transition-colors" wire:navigate>
                    {{ __('Sign In') }}
                </a>
            </p>
        </div>
    </div>
</x-layouts::auth>