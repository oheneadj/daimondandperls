<section>
    <x-settings.layout :title="__('Notification Settings')" :description="__('Configure how you receive administrative alerts')">
        <div class="max-w-2xl">
            <form wire:submit="updateNotifications" class="space-y-6">

                {{-- System-wide toggles --}}
                <div class="bg-white border border-base-content/5 rounded-xl shadow-sm overflow-hidden">
                    <div class="px-6 py-4 border-b border-base-content/5">
                        <p class="text-[11px] font-bold uppercase tracking-widest text-base-content/50">{{ __('System Channels') }}</p>
                        <p class="text-[12px] text-base-content/40 font-medium mt-0.5">{{ __('Master switches for outgoing notifications.') }}</p>
                    </div>
                    <div class="divide-y divide-base-content/5">
                        <label class="flex items-center justify-between px-6 py-4 cursor-pointer hover:bg-base-200/30 transition-colors">
                            <div>
                                <p class="text-[13px] font-bold text-base-content">{{ __('Email Notifications') }}</p>
                                <p class="text-[11px] text-base-content/40 font-medium mt-0.5">{{ __('Enable all outgoing booking and system emails.') }}</p>
                            </div>
                            <input type="checkbox" wire:model="email_enabled" class="toggle toggle-primary toggle-sm" />
                        </label>
                        <label class="flex items-center justify-between px-6 py-4 cursor-pointer hover:bg-base-200/30 transition-colors">
                            <div>
                                <p class="text-[13px] font-bold text-base-content">{{ __('SMS Notifications') }}</p>
                                <p class="text-[11px] text-base-content/40 font-medium mt-0.5">{{ __('Enable all outgoing SMS alerts via GaintSMS.') }}</p>
                            </div>
                            <input type="checkbox" wire:model="sms_enabled" class="toggle toggle-primary toggle-sm" />
                        </label>
                    </div>
                </div>

                {{-- Personal preference --}}
                <div class="bg-white border border-base-content/5 rounded-xl shadow-sm overflow-hidden">
                    <div class="px-6 py-4 border-b border-base-content/5">
                        <p class="text-[11px] font-bold uppercase tracking-widest text-base-content/50">{{ __('Personal Preference') }}</p>
                        <p class="text-[12px] text-base-content/40 font-medium mt-0.5">{{ __('How you personally receive admin alerts.') }}</p>
                    </div>
                    <div class="p-6 space-y-3">
                        @foreach([
                            ['value' => 'email', 'label' => 'Email Only', 'desc' => 'Receive alerts via email only'],
                            ['value' => 'sms',   'label' => 'SMS Only',   'desc' => 'Receive alerts via SMS only'],
                            ['value' => 'both',  'label' => 'Email & SMS','desc' => 'Receive alerts via both channels'],
                        ] as $option)
                            <label class="flex items-center gap-3 p-4 rounded-xl border cursor-pointer transition-all
                                {{ $notification_preference === $option['value'] ? 'border-primary/25 bg-primary/[0.03]' : 'border-base-content/8 hover:border-base-content/15' }}">
                                <input type="radio" wire:model.live="notification_preference" value="{{ $option['value'] }}"
                                    class="radio radio-primary radio-sm">
                                <div>
                                    <p class="text-[13px] font-bold text-base-content">{{ __($option['label']) }}</p>
                                    <p class="text-[11px] text-base-content/45 font-medium">{{ __($option['desc']) }}</p>
                                </div>
                            </label>
                        @endforeach
                        @error('notification_preference') <p class="text-[12px] text-error font-bold mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div class="px-6 py-4 border-t border-base-content/5 bg-base-200/30 flex items-center justify-between gap-4">
                        <x-action-message class="text-[12px] font-bold text-success flex items-center gap-1.5" on="notifications-updated">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            {{ __('Saved') }}
                        </x-action-message>
                        <div></div>
                        <button type="submit" wire:loading.attr="disabled"
                            class="inline-flex items-center gap-2 px-6 py-2.5 bg-primary text-white rounded-lg font-bold text-[13px] hover:bg-primary/90 transition-colors disabled:opacity-50">
                            <span wire:loading.remove wire:target="updateNotifications">{{ __('Save Preferences') }}</span>
                            <span wire:loading wire:target="updateNotifications" class="flex items-center gap-2">
                                <span class="loading loading-spinner loading-xs"></span>
                                {{ __('Saving...') }}
                            </span>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </x-settings.layout>
</section>
