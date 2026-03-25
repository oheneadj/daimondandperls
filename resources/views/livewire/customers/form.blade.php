<div class="space-y-10 pb-10">
    <!-- Header Section -->
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-6">
        <div>
            <h1 class="text-4xl font-semibold tracking-tight text-base-content leading-tight">
                {{ $isEditing ? __('Edit Customer') : __('New Customer') }}
            </h1>
            <p class="text-base-content/60 font-normal mt-2">
                {{ $isEditing ? __('Update details for') . ' ' . $customer->name : __('Create a new customer profile for your catering business.') }}
            </p>
        </div>
        
        <x-ui.button variant="black" size="sm" href="{{ route('admin.customers.index') }}" wire:navigate title="{{ __('Back to Customers') }}">
            <x-slot:icon>
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
            </x-slot:icon>
            {{ __('Back to Customers') }}
        </x-ui.button>
    </div>

    <form wire:submit="save" class="max-w-3xl space-y-8">
        <x-ui.card>
            <div class="space-y-8">
                <div class="flex items-center gap-2.5 mb-2">
                    <div class="w-8 h-8 rounded-full bg-primary/10 text-primary flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                    </div>
                    <h2 class="text-[11px] font-bold uppercase tracking-[0.2em] text-base-content">{{ __('Customer Details') }}</h2>
                </div>

                <div class="space-y-6">
                    <!-- Name -->
                    <div class="space-y-2">
                        <label class="text-[11px] font-bold uppercase tracking-widest text-base-content/60">{{ __('Full Name') }} <span class="text-primary">*</span></label>
                        <x-ui.input wire:model="name" placeholder="e.g. Ohene Adjei" required />
                        @error('name') <p class="text-[11px] text-error font-bold mt-1.5">{{ $message }}</p> @enderror
                    </div>

                    <!-- Email and Phone Grid -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <!-- Email -->
                        <div class="space-y-2">
                            <label class="text-[11px] font-bold uppercase tracking-widest text-base-content/60">{{ __('Email Address') }}</label>
                            <x-ui.input wire:model="email" type="email" placeholder="customer@example.com" />
                            @error('email') <p class="text-[11px] text-error font-bold mt-1.5">{{ $message }}</p> @enderror
                        </div>

                        <!-- Phone -->
                        <div class="space-y-2">
                            <label class="text-[11px] font-bold uppercase tracking-widest text-base-content/60">{{ __('Phone Number') }} <span class="text-primary">*</span></label>
                            <x-ui.input wire:model="phone" type="tel" placeholder="+233..." required />
                            @error('phone') <p class="text-[11px] text-error font-bold mt-1.5">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </div>

                @if($hasUser && Auth::user()->role === \App\Enums\UserRole::SuperAdmin)
                    <div class="pt-8 border-t border-base-content/5">
                        <div class="flex items-center justify-between p-4 rounded-xl bg-base-200/30 border border-base-content/5">
                            <div class="space-y-1">
                                <p class="text-[13px] font-bold text-base-content">{{ __('Active Account Access') }}</p>
                                <p class="text-[11px] text-base-content/50">{{ __('When disabled, this customer will be blocked from logging into the platform.') }}</p>
                            </div>
                            <x-ui.toggle wire:model="is_active" />
                        </div>
                    </div>
                @endif
            </div>
        </x-ui.card>

        <div class="flex items-center justify-between pt-4">
            <x-ui.button variant="black" href="{{ route('admin.customers.index') }}" wire:navigate>
                {{ __('Cancel') }}
            </x-ui.button>
            <x-ui.button type="submit" variant="primary" size="lg" wire:loading.attr="disabled" :loading="$loading === 'save'" class="min-w-[200px] shadow-dp-lg">
                {{ $isEditing ? __('Update Customer') : __('Create Customer') }}
            </x-ui.button>
        </div>
    </form>
</div>