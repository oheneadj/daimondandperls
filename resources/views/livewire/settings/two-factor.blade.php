<section>
    <x-settings.layout :title="__('Two-Factor Authentication')" :description="__('Add an extra layer of security to your account')">
        <div class="max-w-2xl" wire:cloak>
            <div class="bg-white border border-base-content/5 rounded-xl shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-base-content/5 flex items-center justify-between">
                    <p class="text-[11px] font-bold uppercase tracking-widest text-base-content/50">{{ __('2FA Status') }}</p>
                    @if ($twoFactorEnabled)
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-success/10 text-success text-[11px] font-bold uppercase tracking-widest">
                            <span class="w-1.5 h-1.5 rounded-full bg-success"></span>
                            {{ __('Enabled') }}
                        </span>
                    @else
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-base-200 text-base-content/40 text-[11px] font-bold uppercase tracking-widest">
                            <span class="w-1.5 h-1.5 rounded-full bg-base-content/30"></span>
                            {{ __('Disabled') }}
                        </span>
                    @endif
                </div>
                <div class="p-6 space-y-6">
                    @if ($twoFactorEnabled)
                        <p class="text-[13px] text-base-content/60 font-medium leading-relaxed">
                            {{ __('Two-factor authentication is active. You will be prompted for a secure pin from your authenticator app when logging in.') }}
                        </p>

                        <livewire:settings.two-factor.recovery-codes :$requiresConfirmation />

                        <div class="border-t border-base-content/5 pt-5">
                            <button wire:click="disable"
                                class="inline-flex items-center gap-2 px-5 py-2.5 bg-[#D52518]/10 text-[#D52518] border border-[#D52518]/20 rounded-lg font-bold text-[13px] hover:bg-[#D52518]/20 transition-colors">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636" /></svg>
                                {{ __('Disable 2FA') }}
                            </button>
                        </div>
                    @else
                        <p class="text-[13px] text-base-content/60 font-medium leading-relaxed">
                            {{ __('When enabled, you will be prompted for a secure, random pin during login. Retrieve this pin from a TOTP-supported app on your phone (e.g. Google Authenticator, Authy).') }}
                        </p>
                        <button wire:click="enable"
                            class="inline-flex items-center gap-2 px-5 py-2.5 bg-primary text-white rounded-lg font-bold text-[13px] hover:bg-primary/90 transition-colors shadow-sm">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 21a11.955 11.955 0 01-9.618-7.016m19.236 0A11.955 11.955 0 0112 2.984a11.955 11.955 0 019.618 7.016z" /></svg>
                            {{ __('Enable 2FA') }}
                        </button>
                    @endif
                </div>
            </div>
        </div>
    </x-settings.layout>

    {{-- Setup Modal --}}
    <dialog id="two_factor_setup_modal" class="modal modal-bottom sm:modal-middle"
        x-data="{ show: @entangle('showModal') }" x-show="show"
        x-init="$watch('show', value => value ? $el.showModal() : $el.close())">
        <div class="modal-box bg-white border border-base-content/5 p-8 flex flex-col gap-8 rounded-2xl">
            <div class="flex flex-col items-center text-center gap-4">
                <div class="w-16 h-16 rounded-2xl bg-primary/10 flex items-center justify-center text-primary">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z" />
                    </svg>
                </div>
                <div>
                    <h3 class="font-bold text-[18px] text-base-content">{{ $this->modalConfig['title'] }}</h3>
                    <p class="text-[13px] text-base-content/50 font-medium mt-1">{{ $this->modalConfig['description'] }}</p>
                </div>
            </div>

            @if ($showVerificationStep)
                <div class="space-y-6">
                    <div>
                        <label class="text-[11px] font-bold uppercase tracking-widest text-base-content/50 block mb-2">{{ __('Verification Code') }}</label>
                        <input wire:model="code" type="text" inputmode="numeric" pattern="[0-9]*" maxlength="6"
                            class="input input-bordered w-full text-center text-[24px] font-mono tracking-[0.5em] h-14 border-base-content/15 rounded-xl"
                            placeholder="000000" />
                    </div>
                    <div class="flex gap-3">
                        <button type="button" wire:click="resetVerification"
                            class="flex-1 px-4 py-2.5 border border-base-content/15 rounded-lg font-bold text-[13px] text-base-content/60 hover:text-base-content hover:border-base-content/30 transition-colors">
                            {{ __('Back') }}
                        </button>
                        <button type="button" wire:click="confirmTwoFactor" @disabled(strlen($code) < 6)
                            class="flex-1 px-4 py-2.5 bg-primary text-white rounded-lg font-bold text-[13px] hover:bg-primary/90 transition-colors disabled:opacity-50">
                            {{ __('Confirm') }}
                        </button>
                    </div>
                </div>
            @else
                <div class="flex flex-col items-center gap-6">
                    <div class="p-4 bg-white rounded-xl shadow-md border border-base-content/5">
                        @empty($qrCodeSvg)
                            <div class="w-48 h-48 flex items-center justify-center bg-base-200 animate-pulse rounded-xl">
                                <span class="loading loading-spinner loading-md text-primary"></span>
                            </div>
                        @else
                            <div class="w-48 h-48 flex items-center justify-center">
                                {!! $qrCodeSvg !!}
                            </div>
                        @endempty
                    </div>

                    <button type="button" wire:click="showVerificationIfNecessary"
                        class="w-full px-6 py-2.5 bg-primary text-white rounded-lg font-bold text-[13px] hover:bg-primary/90 transition-colors">
                        {{ $this->modalConfig['buttonText'] }}
                    </button>

                    <div class="w-full border-t border-base-content/5 pt-4 space-y-2">
                        <p class="text-[11px] font-bold uppercase tracking-widest text-base-content/40 text-center">{{ __('Or enter manually') }}</p>
                        <div class="flex items-center gap-2 bg-base-200 rounded-lg p-1">
                            <input type="text" readonly value="{{ $manualSetupKey }}"
                                class="flex-1 bg-transparent px-2 font-mono text-[12px] text-center tracking-wider border-none outline-none text-base-content/70" />
                            <button type="button"
                                onclick="navigator.clipboard.writeText('{{ $manualSetupKey }}'); this.textContent='Copied!'; setTimeout(() => this.textContent='Copy', 1500)"
                                class="px-3 py-1.5 bg-primary text-white rounded-md font-bold text-[11px] hover:bg-primary/90 transition-colors">
                                Copy
                            </button>
                        </div>
                    </div>
                </div>
            @endif
        </div>
        <form method="dialog" class="modal-backdrop bg-neutral-900/70 backdrop-blur-sm">
            <button @click="show = false">close</button>
        </form>
    </dialog>
</section>
