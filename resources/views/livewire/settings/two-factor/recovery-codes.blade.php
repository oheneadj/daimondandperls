<div class="bg-base-200/50 border border-base-content/5 rounded-2xl p-6 space-y-6" wire:cloak
    x-data="{ showRecoveryCodes: false }">
    <div class="flex flex-col gap-2">
        <div class="flex items-center gap-3">
            <div class="p-2 bg-primary/10 text-primary rounded-lg">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                </svg>
            </div>
            <h4 class="font-bold text-lg leading-none">{{ __('2FA recovery codes') }}</h4>
        </div>
        <p class="text-xs text-base-content/50 italic font-medium">
            {{ __('Recovery codes let you regain access if you lose your 2FA device. Store them in a secure password manager.') }}
        </p>
    </div>

    <div class="flex flex-col gap-4">
        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <x-ui.button type="button" x-show="!showRecoveryCodes" variant="primary" size="sm" class="rounded-xl font-bold uppercase tracking-widest text-[10px] px-6" @click="showRecoveryCodes = true;">
                <x-slot:icon>
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                    </svg>
                </x-slot:icon>
                {{ __('View Codes') }}
            </x-ui.button>

            <x-ui.button type="button" x-show="showRecoveryCodes" variant="ghost" size="sm" class="rounded-xl font-bold uppercase tracking-widest text-[10px]" @click="showRecoveryCodes = false">
                <x-slot:icon>
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.04m2.458-2.389A9.987 9.987 0 0112 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-1.446 0-2.842-.303-4.108-.853M15 12a3 3 0 11-6 0 3 3 0 016 0zm-1.5 1.5l-3-3" />
                    </svg>
                </x-slot:icon>
                {{ __('Hide Codes') }}
            </x-ui.button>

            @if (filled($recoveryCodes))
                <x-ui.button type="button" x-show="showRecoveryCodes" variant="outline" size="sm" class="rounded-xl font-bold uppercase tracking-widest text-[10px]" wire:click="regenerateRecoveryCodes">
                    <x-slot:icon>
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                        </svg>
                    </x-slot:icon>
                    {{ __('Regenerate') }}
                </x-ui.button>
            @endif
        </div>

        <div x-show="showRecoveryCodes" x-transition class="relative overflow-hidden">
            <div class="mt-4 space-y-4">
                @error('recoveryCodes')
                    <div class="alert alert-error shadow-sm rounded-xl py-2 px-4">
                        <span class="text-xs font-bold uppercase tracking-widest">{{$message}}</span>
                    </div>
                @enderror

                @if (filled($recoveryCodes))
                    <div
                        class="grid grid-cols-1 sm:grid-cols-2 gap-3 p-6 font-mono text-sm rounded-2xl bg-base-300/50 border border-base-content/5 shadow-inner">
                        @foreach($recoveryCodes as $code)
                            <div class="bg-base-100 p-3 rounded-lg text-center font-bold tracking-widest text-primary shadow-sm group hover:scale-105 transition-transform"
                                wire:loading.class="opacity-50 animate-pulse">
                                {{ $code }}
                            </div>
                        @endforeach
                    </div>
                    <p class="text-[10px] text-base-content/40 font-bold uppercase tracking-[0.2em] italic text-center">
                        {{ __('Each recovery code can be used once and will be removed after use.') }}
                    </p>
                @endif
            </div>
        </div>
    </div>
</div>