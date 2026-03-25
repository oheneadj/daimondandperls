<section class="max-w-2xl">
    <x-settings.layout :title="__('Payment Settings')" :description="__('Configure your payment gateways and API keys')">
        <form wire:submit="updateSettings" class="space-y-10 mt-6">
            <!-- Payment Gateway -->
            <div class="space-y-4">
                <label class=" text-[11px] font-bold uppercase tracking-[0.2em] text-base-content/60 ml-1">{{ __('Active Acquisition Gateway') }}</label>
                <x-ui.select wire:model="settings.payment_gateway" class="rounded-2xl h-14 bg-base-100 border-base-content/10/60">
                    <option value="paystack">{{ __('Paystack Collective') }}</option>
                    <option value="manual">{{ __('Manual Registry / Bank Transfer') }}</option>
                </x-ui.select>
                @error('settings.payment_gateway') <p class=" text-[11px] text-error font-bold mt-1.5 ml-1">{{ $message }}</p> @enderror
            </div>

            <div x-show="$wire.settings.payment_gateway === 'paystack'" class="space-y-8 animate-in slide-in-from-top-2 duration-400">
                <!-- Paystack Public Key -->
                <div class="space-y-2.5">
                    <label class=" text-[11px] font-bold uppercase tracking-[0.2em] text-base-content/60 ml-1">{{ __('Paystack Public Identifier') }}</label>
                    <x-ui.input type="password" wire:model="settings.paystack_public_key" placeholder="pk_test_..." class="rounded-2xl h-14 bg-base-100 border-base-content/10/60" />
                    @error('settings.paystack_public_key') <p class=" text-[11px] text-error font-bold mt-1.5 ml-1">{{ $message }}</p> @enderror
                </div>

                <!-- Paystack Secret Key -->
                <div class="space-y-2.5">
                    <label class=" text-[11px] font-bold uppercase tracking-[0.2em] text-base-content/60 ml-1">{{ __('Paystack Secret Cipher') }}</label>
                    <x-ui.input type="password" wire:model="settings.paystack_secret_key" placeholder="sk_test_..." class="rounded-2xl h-14 bg-base-100 border-base-content/10/60" />
                    @error('settings.paystack_secret_key') <p class=" text-[11px] text-error font-bold mt-1.5 ml-1">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="flex items-center gap-6 pt-10 border-t border-base-content/10/50">
                <x-ui.button type="submit" variant="primary" size="lg" class="px-10 h-14 rounded-2xl shadow-dp-lg  text-[14px] uppercase tracking-widest">
                    {{ __('Secure Gateway') }}
                </x-ui.button>

                <x-action-message class=" text-[13px] font-bold text-secondary bg-secondary-soft px-4 py-2 rounded-xl border border-dp-green/10" on="settings-updated">
                    {{ __('Credential Secured') }}
                </x-action-message>
            </div>
        </form>
    </x-settings.layout>
</section>
