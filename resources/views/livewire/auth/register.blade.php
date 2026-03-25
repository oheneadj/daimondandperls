<x-layouts::auth :title="__('Register')">
    <div class="space-y-12">
        <!-- Header -->
        <div class="text-center space-y-2">
            <h1 class=" text-3xl sm:text-4xl font-semibold tracking-tight text-base-content">
                {{ __('New Application') }}
            </h1>
            <p class=" text-[14px] text-base-content/60 font-medium max-w-[300px] mx-auto leading-relaxed italic">
                {{ __('Submit your details to applied for access to the Diamonds & Pearls coordination platform.') }}
            </p>
        </div>

        <form method="POST" action="{{ route('register.store') }}" class="space-y-8">
            @csrf

            <!-- Identity Group -->
            <div class="space-y-6">
                <!-- Name -->
                <div class="space-y-2.5">
                    <label class="text-[11px] font-bold uppercase tracking-widest text-base-content/60 ml-0.5">{{ __('Full Name') }}</label>
                    <x-ui.input name="name" type="text" value="{{ old('name') }}" required autofocus autocomplete="name" placeholder="e.g. Samuel Osei" class="rounded-xl h-14 bg-base-100 border-base-content/10/60 focus:ring-4 focus:ring-primary/20 shadow-sm" />
                    @error('name') <p class="text-[12px] font-bold text-error mt-1.5 ml-1 tracking-tight">{{ $message }}</p> @enderror
                </div>

                <!-- Email Address -->
                <div class="space-y-2.5">
                    <label class="text-[11px] font-bold uppercase tracking-widest text-base-content/60 ml-0.5">{{ __('Email Address') }}</label>
                    <x-ui.input name="email" type="email" value="{{ old('email') }}" required autocomplete="email" placeholder="e.g. staff@dpcatering.com" class="rounded-xl h-14 bg-base-100 border-base-content/10/60 focus:ring-4 focus:ring-primary/20 shadow-sm" />
                    @error('email') <p class="text-[12px] font-bold text-error mt-1.5 ml-1 tracking-tight">{{ $message }}</p> @enderror
                </div>

                <!-- Password -->
                <div class="space-y-2.5">
                    <label class="text-[11px] font-bold uppercase tracking-widest text-base-content/60 ml-0.5">{{ __('Access Password') }}</label>
                    <x-ui.input name="password" type="password" required autocomplete="new-password" placeholder="••••••••" class="rounded-xl h-14 bg-base-100 border-base-content/10/60 focus:ring-4 focus:ring-primary/20 shadow-sm" />
                    @error('password') <p class="text-[12px] font-bold text-error mt-1.5 ml-1 tracking-tight">{{ $message }}</p> @enderror
                </div>

                <!-- Confirm Password -->
                <div class="space-y-2.5">
                    <label class="text-[11px] font-bold uppercase tracking-widest text-base-content/60 ml-0.5">{{ __('Confirm Password') }}</label>
                    <x-ui.input name="password_confirmation" type="password" required autocomplete="new-password" placeholder="••••••••" class="rounded-xl h-14 bg-base-100 border-base-content/10/60 focus:ring-4 focus:ring-primary/20 shadow-sm" />
                    @error('password_confirmation') <p class="text-[12px] font-bold text-error mt-1.5 ml-1 tracking-tight">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="pt-4">
                <x-ui.button type="submit" variant="primary" size="lg" class="w-full h-14 rounded-xl shadow-md text-[14px] uppercase tracking-widest">
                    {{ __('Submit Application') }}
                </x-ui.button>
            </div>
        </form>

        <div class="text-center pt-8 border-t border-dp-pearl-mid">
            <p class="text-[13px] text-base-content/60 font-medium">
                {{ __('Already a team member?') }}
                <a href="{{ route('login') }}" class="text-primary font-bold hover:text-primary-dark transition-colors ml-1" wire:navigate>
                    {{ __('Sign In') }}
                </a>
            </p>
        </div>
    </div>
</x-layouts::auth>