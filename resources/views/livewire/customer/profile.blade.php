<div class="space-y-6">
    {{-- Page Header --}}
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-4">
        <div>
            <h1 class="text-[28px] font-semibold text-base-content leading-tight">{{ __('Profile Settings') }}</h1>
            <p class="text-[14px] text-base-content/50 mt-1">{{ __('Update your contact details and notification preferences.') }}</p>
        </div>
    </div>

    <form wire:submit="save" class="grid grid-cols-1 lg:grid-cols-3 gap-6 items-start">
        {{-- Left column: avatar / quick info --}}
        <div class="lg:col-span-1 space-y-4">
            <div class="bg-white border border-base-content/5 rounded-xl p-6 flex flex-col items-center gap-4 text-center shadow-sm">
                <div class="w-20 h-20 rounded-full bg-primary/10 flex items-center justify-center text-primary font-bold text-[28px]">
                    {{ strtoupper(substr($name ?: 'U', 0, 1)) }}
                </div>
                <div>
                    <p class="text-[15px] font-bold text-base-content">{{ $name ?: __('Your Name') }}</p>
                    <p class="text-[12px] text-base-content/40 mt-0.5">{{ $email ?: __('No email set') }}</p>
                    <p class="text-[12px] text-base-content/40">{{ $phone ?: __('No phone set') }}</p>
                </div>
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-success/10 text-success text-[11px] font-bold uppercase tracking-widest">
                    <span class="w-1.5 h-1.5 rounded-full bg-success"></span>
                    {{ __('Customer') }}
                </span>
            </div>

            {{-- Tips card --}}
            <div class="bg-primary/5 border border-primary/10 rounded-xl p-5">
                <p class="text-[11px] font-bold uppercase tracking-widest text-primary mb-3">{{ __('Why keep this updated?') }}</p>
                <ul class="space-y-2 text-[12px] text-base-content/60 font-medium">
                    <li class="flex items-start gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 text-primary mt-0.5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        {{ __('Receive booking confirmations and updates.') }}
                    </li>
                    <li class="flex items-start gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 text-primary mt-0.5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        {{ __('Faster checkout with pre-filled details.') }}
                    </li>
                    <li class="flex items-start gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 text-primary mt-0.5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        {{ __('We can reach you if there is an issue.') }}
                    </li>
                </ul>
            </div>
        </div>

        {{-- Right column: forms --}}
        <div class="lg:col-span-2 space-y-6">
            {{-- Contact Details --}}
            <div class="bg-white border border-base-content/5 rounded-xl shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-base-content/5">
                    <h2 class="text-[11px] font-bold uppercase tracking-widest text-base-content/50">{{ __('Contact Details') }}</h2>
                </div>
                <div class="p-6 space-y-5">
                    <x-app.input
                        name="name"
                        type="text"
                        label="Full Name"
                        wire:model.live="name"
                        placeholder="Your full name"
                    />
                    @error('name') <p class="text-[12px] font-bold text-error -mt-3">{{ $message }}</p> @enderror

                    <x-app.input
                        name="email"
                        type="email"
                        label="Email Address"
                        wire:model="email"
                        placeholder="your@email.com"
                    />
                    @error('email') <p class="text-[12px] font-bold text-error -mt-3">{{ $message }}</p> @enderror

                    <x-app.input
                        name="phone"
                        type="tel"
                        label="Phone Number"
                        wire:model="phone"
                        placeholder="024XXXXXXX"
                    />
                    @error('phone') <p class="text-[12px] font-bold text-error -mt-3">{{ $message }}</p> @enderror
                </div>
            </div>

            {{-- Notification Preferences --}}
            <div class="bg-white border border-base-content/5 rounded-xl shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-base-content/5">
                    <h2 class="text-[11px] font-bold uppercase tracking-widest text-base-content/50">{{ __('Notification Preferences') }}</h2>
                    <p class="text-[12px] text-base-content/40 font-medium mt-0.5">{{ __('How would you like to receive booking updates?') }}</p>
                </div>
                <div class="p-6 space-y-3">
                    @foreach(\App\Enums\NotificationPreference::cases() as $pref)
                        <label class="flex items-center gap-3 p-4 rounded-xl border cursor-pointer transition-all
                            {{ $notificationPreference === $pref->value ? 'border-primary/25 bg-primary/[0.03]' : 'border-base-content/8 hover:border-base-content/15' }}">
                            <input type="radio" wire:model.live="notificationPreference" value="{{ $pref->value }}"
                                class="radio radio-primary radio-sm">
                            <div>
                                <div class="text-[13px] font-bold text-base-content">{{ $pref->name }}</div>
                                <div class="text-[11px] text-base-content/45 font-medium">
                                    @switch($pref)
                                        @case(\App\Enums\NotificationPreference::Email)
                                            {{ __('Receive updates via email only') }}
                                            @break
                                        @case(\App\Enums\NotificationPreference::Sms)
                                            {{ __('Receive updates via SMS only') }}
                                            @break
                                        @case(\App\Enums\NotificationPreference::Both)
                                            {{ __('Receive updates via both email and SMS') }}
                                            @break
                                    @endswitch
                                </div>
                            </div>
                        </label>
                    @endforeach
                    @error('notificationPreference') <p class="text-[12px] font-bold text-error mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            {{-- Save --}}
            <div class="flex justify-end">
                <button type="submit" wire:loading.attr="disabled" wire:target="save"
                    class="inline-flex items-center gap-2 px-8 py-3 bg-primary text-white rounded-xl font-bold text-[13px] hover:bg-primary/90 transition-colors shadow-sm disabled:opacity-50">
                    <span wire:loading.remove wire:target="save">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 inline -mt-0.5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        {{ __('Save Changes') }}
                    </span>
                    <span wire:loading wire:target="save" class="flex items-center gap-2">
                        <span class="loading loading-spinner loading-xs"></span>
                        {{ __('Saving...') }}
                    </span>
                </button>
            </div>
        </div>
    </form>
</div>
