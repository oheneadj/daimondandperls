<section>
    <x-settings.layout :title="__('Payment Settings')" :description="__('Configure your payment gateways and API keys')">
        <div class="max-w-2xl">
            <form wire:submit="updateSettings">
                <div class="bg-white border border-base-content/5 rounded-xl shadow-sm overflow-hidden">
                    <div class="px-6 py-4 border-b border-base-content/5">
                        <p class="text-[11px] font-bold uppercase tracking-widest text-base-content/50">{{ __('Gateway Configuration') }}</p>
                    </div>
                    <div class="p-6 space-y-5">
                        <div>
                            <label class="text-[11px] font-bold uppercase tracking-widest text-base-content/50 block mb-1.5">{{ __('Active Payment Gateway') }}</label>
                            <x-ui.select wire:model.live="settings.payment_gateway">
                                <option value="paystack">{{ __('Paystack') }}</option>
                                <option value="manual">{{ __('Manual / Bank Transfer') }}</option>
                            </x-ui.select>
                            @error('settings.payment_gateway') <p class="text-[12px] text-error font-bold mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div x-data x-show="$wire.settings.payment_gateway === 'paystack'" class="space-y-5 border-t border-base-content/5 pt-5">
                            <div class="flex items-center gap-2 mb-1">
                                <div class="w-7 h-7 rounded-lg bg-primary/10 flex items-center justify-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z" /></svg>
                                </div>
                                <p class="text-[12px] font-bold text-base-content">{{ __('Paystack API Keys') }}</p>
                            </div>
                            <div>
                                <label class="text-[11px] font-bold uppercase tracking-widest text-base-content/50 block mb-1.5">{{ __('Public Key') }}</label>
                                <x-ui.input type="password" wire:model="settings.paystack_public_key" placeholder="pk_test_..." />
                                @error('settings.paystack_public_key') <p class="text-[12px] text-error font-bold mt-1">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label class="text-[11px] font-bold uppercase tracking-widest text-base-content/50 block mb-1.5">{{ __('Secret Key') }}</label>
                                <x-ui.input type="password" wire:model="settings.paystack_secret_key" placeholder="sk_test_..." />
                                @error('settings.paystack_secret_key') <p class="text-[12px] text-error font-bold mt-1">{{ $message }}</p> @enderror
                            </div>
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
