<x-layouts::auth :title="__('Reset Password')">
    <div class="space-y-12">
        <!-- Header -->
        <div class="text-center space-y-2">
            <h1 class=" text-4xl font-semibold tracking-tight text-base-content">
                {{ __('Secure New Access') }}
            </h1>
            <p class=" text-[14px] text-base-content/60 font-medium max-w-[300px] mx-auto leading-relaxed italic">
                {{ __('Please enter your new access credentials below to restore your coordination capabilities.') }}
            </p>
        </div>

        <form method="POST" action="{{ route('password.update') }}" class="space-y-8">
            @csrf
            <!-- Token -->
            <input type="hidden" name="token" value="{{ request()->route('token') }}">

            <!-- Identity & Secret -->
            <div class="space-y-6">
                <!-- Email Address -->
                <div class="space-y-2.5">
                    <label class="text-[11px] font-bold uppercase tracking-widest text-base-content/60 ml-0.5">{{ __('Email Address') }}</label>
                    <x-ui.input name="email" type="email" value="{{ request('email') }}" required autocomplete="email" placeholder="e.g. staff@dpcatering.com" class="rounded-xl h-14 bg-base-100 border-base-content/10/60 focus:ring-4 focus:ring-primary/20 shadow-sm" />
                    @error('email') <p class="text-[12px] font-bold text-error mt-1.5 ml-1 tracking-tight">{{ $message }}</p> @enderror
                </div>

                <!-- Password -->
                <div class="space-y-2.5">
                    <label class="text-[11px] font-bold uppercase tracking-widest text-base-content/60 ml-0.5">{{ __('New Password') }}</label>
                    <x-ui.input name="password" type="password" required autocomplete="new-password" placeholder="••••••••" class="rounded-xl h-14 bg-base-100 border-base-content/10/60 focus:ring-4 focus:ring-primary/20 shadow-sm" />
                    @error('password') <p class="text-[12px] font-bold text-error mt-1.5 ml-1 tracking-tight">{{ $message }}</p> @enderror
                </div>

                <!-- Confirm Password -->
                <div class="space-y-2.5">
                    <label class="text-[11px] font-bold uppercase tracking-widest text-base-content/60 ml-0.5">{{ __('Confirm New Password') }}</label>
                    <x-ui.input name="password_confirmation" type="password" required autocomplete="new-password" placeholder="••••••••" class="rounded-xl h-14 bg-base-100 border-base-content/10/60 focus:ring-4 focus:ring-primary/20 shadow-sm" />
                    @error('password_confirmation') <p class="text-[12px] font-bold text-error mt-1.5 ml-1 tracking-tight">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="pt-4">
                <x-ui.button type="submit" variant="primary" size="lg" class="w-full h-14 rounded-xl shadow-md text-[14px] uppercase tracking-widest">
                    {{ __('Restore Access') }}
                </x-ui.button>
            </div>
        </form>
    </div>
</x-layouts::auth>