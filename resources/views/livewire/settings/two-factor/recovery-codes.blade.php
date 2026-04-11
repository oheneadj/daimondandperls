<div class="bg-base-200/50 border border-base-content/5 rounded-xl p-5 space-y-4" wire:cloak
    x-data="{ showRecoveryCodes: false }">
    <div class="flex items-center gap-3">
        <div class="w-8 h-8 rounded-lg bg-primary/10 text-primary flex items-center justify-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
            </svg>
        </div>
        <div>
            <p class="text-[13px] font-bold text-base-content">{{ __('Recovery Codes') }}</p>
            <p class="text-[11px] text-base-content/40 font-medium">{{ __('Store these in a secure password manager.') }}</p>
        </div>
    </div>

    <div class="flex flex-wrap items-center gap-2">
        <button type="button" x-show="!showRecoveryCodes" @click="showRecoveryCodes = true"
            class="inline-flex items-center gap-1.5 px-4 py-2 border border-base-content/15 rounded-lg font-bold text-[12px] text-base-content/60 hover:text-base-content hover:border-base-content/30 transition-colors">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
            {{ __('View Codes') }}
        </button>

        <button type="button" x-show="showRecoveryCodes" @click="showRecoveryCodes = false"
            class="inline-flex items-center gap-1.5 px-4 py-2 border border-base-content/15 rounded-lg font-bold text-[12px] text-base-content/60 hover:text-base-content hover:border-base-content/30 transition-colors">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.04m2.458-2.389A9.987 9.987 0 0112 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-1.446 0-2.842-.303-4.108-.853M15 12a3 3 0 11-6 0 3 3 0 016 0zm-1.5 1.5l-3-3" /></svg>
            {{ __('Hide') }}
        </button>

        @if (filled($recoveryCodes))
            <button type="button" x-show="showRecoveryCodes" wire:click="regenerateRecoveryCodes"
                class="inline-flex items-center gap-1.5 px-4 py-2 border border-base-content/15 rounded-lg font-bold text-[12px] text-base-content/60 hover:text-base-content hover:border-base-content/30 transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" /></svg>
                {{ __('Regenerate') }}
            </button>
        @endif
    </div>

    <div x-show="showRecoveryCodes" x-transition>
        @error('recoveryCodes')
            <p class="text-[12px] text-error font-bold">{{ $message }}</p>
        @enderror

        @if (filled($recoveryCodes))
            <div class="grid grid-cols-2 gap-2 p-4 bg-base-100 rounded-xl border border-base-content/5 font-mono">
                @foreach($recoveryCodes as $code)
                    <div class="px-3 py-2 bg-base-200 rounded-lg text-center text-[12px] font-bold tracking-widest text-base-content/70 wire:loading.class='opacity-50'">
                        {{ $code }}
                    </div>
                @endforeach
            </div>
            <p class="text-[11px] text-base-content/40 font-medium text-center mt-2">
                {{ __('Each code can only be used once.') }}
            </p>
        @endif
    </div>
</div>
