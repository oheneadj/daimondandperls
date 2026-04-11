<section>
    <x-settings.layout :title="__('General Settings')" :description="__('Update application-wide configuration')">
        <div class="max-w-2xl">
            <form wire:submit="updateSettings">
                <div class="bg-white border border-base-content/5 rounded-xl shadow-sm overflow-hidden">
                    <div class="px-6 py-4 border-b border-base-content/5">
                        <p class="text-[11px] font-bold uppercase tracking-widest text-base-content/50">{{ __('Business Information') }}</p>
                    </div>
                    <div class="p-6 space-y-5">
                        <div>
                            <label class="text-[11px] font-bold uppercase tracking-widest text-base-content/50 block mb-1.5">{{ __('Business Name') }}</label>
                            <x-ui.input type="text" wire:model="settings.business_name" placeholder="e.g. Diamonds & Pearls Catering" />
                            @error('settings.business_name') <p class="text-[12px] text-error font-bold mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                            <div>
                                <label class="text-[11px] font-bold uppercase tracking-widest text-base-content/50 block mb-1.5">{{ __('Business Email') }}</label>
                                <x-ui.input type="email" wire:model="settings.business_email" placeholder="info@catering.com" />
                                @error('settings.business_email') <p class="text-[12px] text-error font-bold mt-1">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label class="text-[11px] font-bold uppercase tracking-widest text-base-content/50 block mb-1.5">{{ __('Business Phone') }}</label>
                                <x-ui.input type="text" wire:model="settings.business_phone" placeholder="+233..." />
                                @error('settings.business_phone') <p class="text-[12px] text-error font-bold mt-1">{{ $message }}</p> @enderror
                            </div>
                        </div>

                        <div>
                            <label class="text-[11px] font-bold uppercase tracking-widest text-base-content/50 block mb-1.5">{{ __('Business Address') }}</label>
                            <x-ui.textarea wire:model="settings.business_address" rows="3" placeholder="House No, Street, City" />
                            @error('settings.business_address') <p class="text-[12px] text-error font-bold mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div class="border-t border-base-content/5 pt-5">
                            <label class="text-[11px] font-bold uppercase tracking-widest text-base-content/50 block mb-1.5">{{ __('Booking Reference Prefix') }}</label>
                            <x-ui.input type="text" wire:model="settings.booking_ref_prefix" placeholder="DPC" />
                            @error('settings.booking_ref_prefix') <p class="text-[12px] text-error font-bold mt-1">{{ $message }}</p> @enderror
                            <p class="text-[11px] text-base-content/40 font-medium mt-1.5">{{ __('Used in booking references — e.g.') }} <code class="font-mono bg-base-200 px-1.5 py-0.5 rounded text-[10px]">DPC-2024-00001</code></p>
                        </div>
                    </div>
                    <div class="px-6 py-4 border-t border-base-content/5 bg-base-200/30 flex items-center justify-between gap-4">
                        <x-action-message class="text-[12px] font-bold text-success flex items-center gap-1.5" on="settings-updated">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            {{ __('Saved') }}
                        </x-action-message>
                        <div></div>
                        <button type="submit" wire:loading.attr="disabled"
                            class="inline-flex items-center gap-2 px-6 py-2.5 bg-primary text-white rounded-lg font-bold text-[13px] hover:bg-primary/90 transition-colors disabled:opacity-50">
                            <span wire:loading.remove wire:target="updateSettings">{{ __('Save Changes') }}</span>
                            <span wire:loading wire:target="updateSettings" class="flex items-center gap-2">
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
