<section class="max-w-2xl">
    <x-settings.layout :title="__('General Settings')" :description="__('Update application-wide configuration')">
        <form wire:submit="updateSettings" class="space-y-8 mt-6">
            <!-- Business Name -->
            <div class="space-y-2.5">
                <label class=" text-[11px] font-bold uppercase tracking-[0.2em] text-base-content/60 ml-1">{{ __('Business Identity') }}</label>
                <x-ui.input type="text" wire:model="settings.business_name" placeholder="e.g. Diamonds & Pearls Catering" class="rounded-2xl h-14 bg-base-100 border-base-content/10/60" />
                @error('settings.business_name') <p class=" text-[11px] text-error font-bold mt-1.5 ml-1">{{ $message }}</p> @enderror
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <!-- Business Email -->
                <div class="space-y-2.5">
                    <label class=" text-[11px] font-bold uppercase tracking-[0.2em] text-base-content/60 ml-1">{{ __('Business Communication') }}</label>
                    <x-ui.input type="email" wire:model="settings.business_email" placeholder="info@catering.com" class="rounded-2xl h-14 bg-base-100 border-base-content/10/60" />
                    @error('settings.business_email') <p class=" text-[11px] text-error font-bold mt-1.5 ml-1">{{ $message }}</p> @enderror
                </div>

                <!-- Business Phone -->
                <div class="space-y-2.5">
                    <label class=" text-[11px] font-bold uppercase tracking-[0.2em] text-base-content/60 ml-1">{{ __('Direct Hotline') }}</label>
                    <x-ui.input type="text" wire:model="settings.business_phone" placeholder="+233..." class="rounded-2xl h-14 bg-base-100 border-base-content/10/60" />
                    @error('settings.business_phone') <p class=" text-[11px] text-error font-bold mt-1.5 ml-1">{{ $message }}</p> @enderror
                </div>
            </div>

            <!-- Business Address -->
            <div class="space-y-2.5">
                <label class=" text-[11px] font-bold uppercase tracking-[0.2em] text-base-content/60 ml-1">{{ __('Corporate Address') }}</label>
                <x-ui.textarea wire:model="settings.business_address" rows="3" placeholder="Enter physical address..." class="rounded-2xl bg-base-100 border-base-content/10/60" />
                @error('settings.business_address') <p class=" text-[11px] text-error font-bold mt-1.5 ml-1">{{ $message }}</p> @enderror
            </div>

            <!-- Booking Reference Prefix -->
            <div class="space-y-2.5">
                <label class=" text-[11px] font-bold uppercase tracking-[0.2em] text-base-content/60 ml-1">{{ __('Orchestration Prefix') }}</label>
                <x-ui.input type="text" wire:model="settings.booking_ref_prefix" placeholder="DPC" class="rounded-2xl h-14 bg-base-100 border-base-content/10/60" />
                @error('settings.booking_ref_prefix') <p class=" text-[11px] text-error font-bold mt-1.5 ml-1">{{ $message }}</p> @enderror
                <p class=" text-[10px] text-base-content/60 font-bold uppercase tracking-widest opacity-40 ml-1">{{ __('Used for orchestration identifiers (e.g. DPC-2024-XXXX)') }}</p>
            </div>

            <div class="flex items-center gap-6 pt-10 border-t border-base-content/10/50">
                <x-ui.button type="submit" variant="primary" size="lg" class="px-10 h-14 rounded-2xl shadow-dp-lg  text-[14px] uppercase tracking-widest">
                    {{ __('Finalize Configuration') }}
                </x-ui.button>

                <x-action-message class=" text-[13px] font-bold text-secondary bg-secondary-soft px-4 py-2 rounded-xl border border-dp-green/10" on="settings-updated">
                    {{ __('Configuration Secured') }}
                </x-action-message>
            </div>
        </form>
    </x-settings.layout>
</section>
