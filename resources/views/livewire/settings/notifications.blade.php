<section class="max-w-2xl">
    <x-settings.layout :title="__('Notification Settings')" :description="__('Configure how you receive administrative alerts')">
        <form wire:submit="updateNotifications" class="space-y-10 mt-6">
            <!-- Global Admin Toggles -->
            <div class="bg-base-200-mid/40 p-10 rounded-3xl border border-base-content/10/40 space-y-8">
                <div class="flex items-center gap-2.5 mb-2">
                    <div class="w-8 h-8 rounded-full bg-primary-soft text-primary flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                    </div>
                    <h3 class=" text-[11px] font-bold uppercase tracking-[0.2em] text-base-content">{{ __('Global System Toggles') }}</h3>
                </div>
                
                <div class="space-y-6">
                    <label class="flex items-center group cursor-pointer">
                        <input type="checkbox" wire:model="email_enabled" class="sr-only peer">
                        <div class="w-10 h-5 border-base-content/10 rounded-full peer peer-checked:after:translate-x-full after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-base-100 after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-primary shadow-sm"></div>
                        <div class="ml-4">
                            <span class="block  text-[13px] font-bold text-base-content">{{ __('Executive Communication') }}</span>
                            <span class="block  text-[10px] text-base-content/60 font-bold uppercase tracking-widest opacity-60">{{ __('Master switch for all outgoing orchestration emails.') }}</span>
                        </div>
                    </label>

                    <label class="flex items-center group cursor-pointer">
                        <input type="checkbox" wire:model="sms_enabled" class="sr-only peer">
                        <div class="w-10 h-5 border-base-content/10 rounded-full peer peer-checked:after:translate-x-full after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-base-100 after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-primary shadow-sm"></div>
                        <div class="ml-4">
                            <span class="block  text-[13px] font-bold text-base-content">{{ __('Direct SMS Transmission') }}</span>
                            <span class="block  text-[10px] text-base-content/60 font-bold uppercase tracking-widest opacity-60">{{ __('Master switch for all SMS alerts (requires Giant SMS config).') }}</span>
                        </div>
                    </label>
                </div>
            </div>

            <div class="divider text-[10px] font-black uppercase tracking-widest opacity-20">{{ __('Personal Preferences') }}</div>

            <!-- Notification Preference -->
            <div class="space-y-4">
                <label class=" text-[11px] font-bold uppercase tracking-[0.2em] text-base-content/60 ml-1">{{ __('Personal Orchestration Alerts') }}</label>
                <x-ui.select wire:model="notification_preference" class="rounded-2xl h-14 bg-base-100 border-base-content/10/60">
                    <option value="email">{{ __('Email Presentation') }}</option>
                    <option value="sms">{{ __('SMS Transmission') }}</option>
                    <option value="both">{{ __('Unified Communication (Both)') }}</option>
                </x-ui.select>
                @error('notification_preference') <p class=" text-[11px] text-error font-bold mt-1.5 ml-1">{{ $message }}</p> @enderror
                <p class=" text-[10px] text-base-content/60 font-bold uppercase tracking-widest opacity-40 ml-1">
                    {{ __('Choose your preferred channel for receiving administrative alerts.') }}
                </p>
            </div>

            <div class="flex items-center gap-6 pt-10 border-t border-base-content/10/50">
                <x-ui.button type="submit" variant="primary" size="lg" class="px-10 h-14 rounded-2xl shadow-dp-lg  text-[14px] uppercase tracking-widest">
                    {{ __('Save Preferences') }}
                </x-ui.button>

                <x-action-message class=" text-[13px] font-bold text-secondary bg-secondary-soft px-4 py-2 rounded-xl border border-dp-green/10" on="notifications-updated">
                    {{ __('Preferences Secured') }}
                </x-action-message>
            </div>
        </form>
    </x-settings.layout>
</section>
