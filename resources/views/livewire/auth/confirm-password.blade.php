<x-layouts::auth :title="__('Confirm Password')">
    <div class="space-y-10">
        <!-- Header -->
        <div class="text-center space-y-3">
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-primary/10 text-primary text-[10px] font-black uppercase tracking-widest mb-2">
                <span class="w-1.5 h-1.5 rounded-full bg-primary animate-pulse"></span>
                {{ __('Secure Area') }}
            </div>
            <h1 class="text-4xl font-black tracking-tight text-base-content leading-tight">
                {{ __('Confirm Access') }}
            </h1>
            <p class="text-[14px] text-base-content/50 font-medium max-w-[280px] mx-auto leading-relaxed italic">
                {{ __('This is a secure area. Please confirm your access password before continuing.') }}
            </p>
        </div>

        <x-auth-session-status :status="session('status')" />

        <form method="POST" action="{{ route('password.confirm.store') }}" class="space-y-8">
            @csrf

            <!-- Password -->
            <div class="space-y-2.5">
                <label class="text-[10px] font-black uppercase tracking-[0.2em] text-base-content/40 ml-1">{{ __('Access Password') }}</label>
                <div class="relative group">
                    <span class="absolute inset-y-0 left-5 flex items-center text-base-content/20 group-focus-within:text-primary transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 00-2 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                    </span>
                    <x-ui.input name="password" type="password" required autocomplete="current-password" placeholder="••••••••" class="pl-14 rounded-[18px] h-14 bg-[#F4F4F6]/50 border-transparent focus:bg-white focus:ring-4 focus:ring-primary/10 shadow-inner group-focus-within:shadow-none transition-all" />
                </div>
                @error('password') <p class="text-[11px] font-bold text-error mt-2 ml-1">{{ $message }}</p> @enderror
            </div>

            <div class="pt-4">
                <button type="submit" class="w-full h-15 bg-[#121212] hover:bg-black text-white rounded-[20px] shadow-xl shadow-black/10 font-black text-[13px] uppercase tracking-[0.2em] transition-all hover:scale-[1.02] active:scale-95 flex items-center justify-center gap-3 group">
                    {{ __('Confirm Access') }}
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 translate-x-0 group-hover:translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </button>
            </div>
        </form>
    </div>
</x-layouts::auth>