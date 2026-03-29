<x-layouts::auth :title="__('Register')" :maxWidth="'max-w-[550px]'">
    <div class="space-y-10">
        <!-- Header -->
        <div class="text-center space-y-3">
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-primary/10 text-primary text-[10px] font-black uppercase tracking-widest mb-2">
                <span class="w-1.5 h-1.5 rounded-full bg-primary animate-pulse"></span>
                {{ __('Client Portal') }}
            </div>
            <h1 class="text-4xl font-black tracking-tight text-base-content leading-tight">
                {{ __('Client Registration') }}
            </h1>
            <p class="text-[14px] text-base-content/50 font-medium max-w-[320px] mx-auto leading-relaxed italic">
                {{ __('Create your client account to start planning your next exquisite event.') }}
            </p>
        </div>

        <form method="POST" action="{{ route('register.store') }}" class="space-y-8">
            @csrf

            <div class="space-y-6">
                <!-- Name -->
                <x-auth.input
                    name="name"
                    type="text"
                    :label="__('Full Name')"
                    :required="true"
                    value="{{ old('name') }}"
                    autofocus
                    autocomplete="name"
                    placeholder="e.g. Ama Mensah"
                >
                    <x-slot:icon>
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                    </x-slot:icon>
                </x-auth.input>

                <!-- Email -->
                <x-auth.input
                    name="email"
                    type="email"
                    :label="__('Email Address')"
                    value="{{ old('email') }}"
                    autocomplete="email"
                    placeholder="e.g. ama@example.com"
                >
                    <x-slot:icon>
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                    </x-slot:icon>
                </x-auth.input>

                <!-- Phone -->
                <x-auth.input
                    name="phone"
                    type="tel"
                    :label="__('Phone Number')"
                    :hint="__('Provide an email, phone number, or both.')"
                    value="{{ old('phone') }}"
                    autocomplete="tel"
                    placeholder="e.g. 0244000000"
                >
                    <x-slot:icon>
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                    </x-slot:icon>
                </x-auth.input>

                <!-- Password -->
                <x-auth.input
                    name="password"
                    type="password"
                    :label="__('Password')"
                    :required="true"
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
                    :required="true"
                    required
                    autocomplete="new-password"
                    placeholder="••••••••"
                >
                    <x-slot:icon>
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                    </x-slot:icon>
                </x-auth.input>
            </div>

            <div class="pt-4">
                <x-auth.button type="submit">
                    {{ __('Create Account') }}
                </x-auth.button>
            </div>
        </form>

        <div class="text-center pt-8 border-t border-base-content/5">
            <p class="text-[12px] text-base-content/40 font-medium tracking-tight">
                {{ __('Already have an account?') }}
                <a href="{{ route('login') }}" class="text-primary font-black uppercase tracking-widest ml-1.5 hover:text-primary-dark transition-colors" wire:navigate>
                    {{ __('Sign In') }}
                </a>
            </p>
        </div>
    </div>
</x-layouts::auth>
