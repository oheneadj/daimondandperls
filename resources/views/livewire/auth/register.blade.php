<x-layouts::auth :title="__('Register')">
    <div class="space-y-10">
        <!-- Header -->
        <div class="text-center space-y-3">
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-primary/10 text-primary text-[10px] font-black uppercase tracking-widest mb-2">
                <span class="w-1.5 h-1.5 rounded-full bg-primary animate-pulse"></span>
                {{ __('Team Enrollment') }}
            </div>
            <h1 class="text-4xl font-black tracking-tight text-base-content leading-tight">
                {{ __('New Account') }}
            </h1>
            <p class="text-[14px] text-base-content/50 font-medium max-w-[280px] mx-auto leading-relaxed italic">
                {{ __('Applied for access to the Diamonds & Pearls coordination platform.') }}
            </p>
        </div>

        <form method="POST" action="{{ route('register.store') }}" class="space-y-8">
            @csrf

            <!-- Identity Group -->
            <div class="space-y-6">
                <!-- Name -->
                <div class="space-y-2.5">
                    <label class="text-[10px] font-black uppercase tracking-[0.2em] text-base-content/40 ml-1">{{ __('Full Name') }}</label>
                    <x-ui.input name="name" type="text" value="{{ old('name') }}" required autofocus autocomplete="name" placeholder="e.g. Samuel Osei" class="rounded-[18px] h-14 bg-[#F4F4F6]/50 border-transparent focus:bg-white focus:ring-4 focus:ring-primary/10 shadow-inner transition-all" />
                    @error('name') <p class="text-[11px] font-bold text-error mt-2 ml-1">{{ $message }}</p> @enderror
                </div>

                <!-- Email Address -->
                <div class="space-y-2.5">
                    <label class="text-[10px] font-black uppercase tracking-[0.2em] text-base-content/40 ml-1">{{ __('Email Address') }}</label>
                    <x-ui.input name="email" type="email" value="{{ old('email') }}" required autocomplete="email" placeholder="e.g. staff@dpcatering.com" class="rounded-[18px] h-14 bg-[#F4F4F6]/50 border-transparent focus:bg-white focus:ring-4 focus:ring-primary/10 shadow-inner transition-all" />
                    @error('email') <p class="text-[11px] font-bold text-error mt-2 ml-1">{{ $message }}</p> @enderror
                </div>

                <!-- Password -->
                <div class="space-y-2.5">
                    <label class="text-[10px] font-black uppercase tracking-[0.2em] text-base-content/40 ml-1">{{ __('Access Password') }}</label>
                    <x-ui.input name="password" type="password" required autocomplete="new-password" placeholder="••••••••" class="rounded-[18px] h-14 bg-[#F4F4F6]/50 border-transparent focus:bg-white focus:ring-4 focus:ring-primary/10 shadow-inner transition-all" />
                    @error('password') <p class="text-[11px] font-bold text-error mt-2 ml-1">{{ $message }}</p> @enderror
                </div>

                <!-- Confirm Password -->
                <div class="space-y-2.5">
                    <label class="text-[10px] font-black uppercase tracking-[0.2em] text-base-content/40 ml-1">{{ __('Confirm Password') }}</label>
                    <x-ui.input name="password_confirmation" type="password" required autocomplete="new-password" placeholder="••••••••" class="rounded-[18px] h-14 bg-[#F4F4F6]/50 border-transparent focus:bg-white focus:ring-4 focus:ring-primary/10 shadow-inner transition-all" />
                    @error('password_confirmation') <p class="text-[11px] font-bold text-error mt-2 ml-1">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="pt-4">
                <button type="submit" class="w-full h-15 bg-[#121212] hover:bg-black text-white rounded-[20px] shadow-xl shadow-black/10 font-black text-[13px] uppercase tracking-[0.2em] transition-all hover:scale-[1.02] active:scale-95 flex items-center justify-center gap-3 group">
                    {{ __('Submit Application') }}
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 translate-x-0 group-hover:translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                    </svg>
                </button>
            </div>
        </form>

        <div class="text-center pt-8 border-t border-base-content/5">
            <p class="text-[12px] text-base-content/40 font-medium tracking-tight">
                {{ __('Already a team member?') }}
                <a href="{{ route('login') }}" class="text-primary font-black uppercase tracking-widest ml-1.5 hover:text-primary-dark transition-colors" wire:navigate>
                    {{ __('Sign In') }}
                </a>
            </p>
        </div>
    </div>
</x-layouts::auth>