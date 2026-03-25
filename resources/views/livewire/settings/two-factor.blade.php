<section class="w-full">
    @include('partials.settings-heading')

    <x-settings.layout :title="__('Two-factor authentication')" :description="__('Manage your two-factor authentication settings')">
        <div class="flex flex-col w-full mx-auto space-y-8 bg-base-100 p-8 rounded-2xl border border-base-content/5 shadow-sm"
            wire:cloak>
            @if ($twoFactorEnabled)
                <div class="flex flex-col gap-6">
                    <div class="flex items-center gap-4">
                        <div
                            class="badge badge-success py-4 px-6 rounded-xl font-bold uppercase tracking-widest text-xs ring-4 ring-success/10">
                            {{ __('Enabled') }}
                        </div>
                    </div>

                    <p class="text-base-content/60 leading-relaxed italic font-medium">
                        {{ __('With two-factor authentication enabled, you will be prompted for a secure, random pin during login, which you can retrieve from the TOTP-supported application on your phone.') }}
                    </p>

                    <livewire:settings.two-factor.recovery-codes :$requiresConfirmation />

                    <div class="flex justify-start pt-6 border-t border-base-content/5 mt-4">
                        <x-ui.button variant="danger" size="sm" class="font-bold uppercase tracking-widest text-[10px]" wire:click="disable">
                            <x-slot:icon>
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                </svg>
                            </x-slot:icon>
                            {{ __('Disable 2FA') }}
                        </x-ui.button>
                    </div>
                </div>
            @else
                <div class="flex flex-col gap-6">
                    <div class="flex items-center gap-4">
                        <div
                            class="badge badge-error badge-outline py-4 px-6 rounded-xl font-bold uppercase tracking-widest text-xs">
                            {{ __('Disabled') }}
                        </div>
                    </div>

                    <p class="text-base-content/60 leading-relaxed italic font-medium">
                        {{ __('When you enable two-factor authentication, you will be prompted for a secure pin during login. This pin can be retrieved from a TOTP-supported application on your phone.') }}
                    </p>

                    <div class="flex justify-start">
                        <x-ui.button variant="primary" size="sm" class="font-bold uppercase tracking-widest text-[10px] px-10" wire:click="enable">
                            <x-slot:icon>
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 21a11.955 11.955 0 01-9.618-7.016m19.236 0A11.955 11.955 0 0112 2.984a11.955 11.955 0 019.618 7.016z" />
                                </svg>
                            </x-slot:icon>
                            {{ __('Enable 2FA') }}
                        </x-ui.button>
                    </div>
                </div>
            @endif
        </div>
    </x-settings.layout>

    <!-- Setup Modal -->
    <dialog id="two_factor_setup_modal" class="modal modal-bottom sm:modal-middle"
        x-data="{ show: @entangle('showModal') }" x-show="show"
        x-init="$watch('show', value => value ? $el.showModal() : $el.close())">
        <div class="modal-box bg-base-100 border border-base-content/5 p-8 flex flex-col gap-8">
            <div class="flex flex-col items-center text-center gap-6">
                <div class="p-4 rounded-3xl bg-primary/5 text-primary border border-primary/10 shadow-inner">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                            d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z" />
                    </svg>
                </div>

                <div class="flex flex-col gap-2">
                    <h3 class="font-black text-2xl tracking-tight">{{ $this->modalConfig['title'] }}</h3>
                    <p class="text-base-content/60 leading-relaxed italic text-sm">
                        {{ $this->modalConfig['description'] }}</p>
                </div>
            </div>

            @if ($showVerificationStep)
                <div class="flex flex-col gap-8">
                    <div class="form-control w-full">
                        <label class="label">
                            <span
                                class="label-text font-bold uppercase tracking-wider text-base-content/50 text-xs">{{ __('Verification Code') }}</span>
                        </label>
                        <input wire:model="code" type="text" inputmode="numeric" pattern="[0-9]*" maxlength="6"
                            class="input input-primary input-bordered w-full text-center text-3xl font-mono tracking-[0.5em] h-16"
                            placeholder="000000" />
                    </div>

                    <div class="flex gap-3 pt-4">
                        <x-ui.button wire:click="resetVerification" variant="ghost" class="flex-1 font-bold uppercase tracking-widest text-[10px]">
                            {{ __('Back') }}
                        </x-ui.button>
                        <x-ui.button wire:click="confirmTwoFactor" variant="primary" class="flex-1 font-bold uppercase tracking-widest text-[10px]" @disabled(strlen($code) < 6)>
                            {{ __('Confirm') }}
                        </x-ui.button>
                    </div>
                </div>
            @else
                <div class="flex flex-col gap-8 items-center">
                    <div class="p-4 bg-white rounded-2xl shadow-xl border border-base-content/5">
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

                    <x-ui.button wire:click="showVerificationIfNecessary" variant="primary" class="w-full font-bold uppercase tracking-widest text-[10px]">
                        {{ $this->modalConfig['buttonText'] }}
                    </x-ui.button>

                    <div class="divider text-[10px] font-bold uppercase tracking-[0.2em] opacity-30">
                        {{ __('or manual key') }}</div>

                    <div class="form-control w-full">
                        <div class="join w-full bg-base-200 p-1 rounded-2xl border border-base-content/5">
                            <input type="text" readonly value="{{ $manualSetupKey }}"
                                class="input input-ghost join-item flex-1 font-mono text-center text-xs tracking-wider border-none focus:ring-0" />
                            <button class="btn btn-primary join-item px-4"
                                onclick="navigator.clipboard.writeText('{{ $manualSetupKey }}'); this.innerHTML='COPIED!'; setTimeout(() => this.innerHTML='COPY', 1500)">
                                COPY
                            </button>
                        </div>
                    </div>
                </div>
            @endif
        </div>
        <form method="dialog" class="modal-backdrop bg-neutral-900/80 backdrop-blur-sm">
            <button @click="show = false">close</button>
        </form>
    </dialog>
</section>