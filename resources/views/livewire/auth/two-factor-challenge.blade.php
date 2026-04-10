<x-layouts::auth :title="__('Two-factor authentication')">
    <div class="space-y-6" x-cloak x-data="{
            showRecoveryInput: @js($errors->has('recovery_code')),
            code: '',
            recovery_code: '',
            toggleInput() {
                this.showRecoveryInput = !this.showRecoveryInput;
                this.code = '';
                this.recovery_code = '';
                $nextTick(() => {
                    if (this.showRecoveryInput) {
                        this.$refs.recovery_code?.focus();
                    }
                });
            },
        }">
        <div x-show="!showRecoveryInput">
            <x-auth-header :title="__('Authentication code')" :description="__('Enter the code from your authenticator app.')" />
        </div>

        <div x-show="showRecoveryInput">
            <x-auth-header :title="__('Recovery code')" :description="__('Enter one of your emergency recovery codes.')" />
        </div>

        <form method="POST" action="{{ route('two-factor.login.store') }}" class="space-y-6">
            @csrf

            <div x-show="!showRecoveryInput" class="form-control w-full space-y-1.5">
                <label class="text-dp-sm font-medium text-base-content block">{{ __('Verification Code') }}</label>
                <input type="text" name="code" inputmode="numeric" pattern="[0-9]*" autocomplete="one-time-code"
                    x-model="code"
                    class="w-full px-[14px] py-[10px] text-center text-2xl font-bold tracking-[0.4em] bg-base-100 border border-base-content/10 rounded-lg transition-all duration-120 outline-none placeholder:text-base-content/20 focus:border-primary focus:ring-3 focus:ring-primary/20 @error('code') border-error focus:ring-error/20 @enderror"
                    placeholder="000000" />
                @error('code')
                    <p class="text-xs text-error flex items-center gap-1"><span>⚠</span> {{ $message }}</p>
                @enderror
            </div>

            <div x-show="showRecoveryInput" class="form-control w-full space-y-1.5">
                <label class="text-dp-sm font-medium text-base-content block">{{ __('Recovery Code') }}</label>
                <input type="text" name="recovery_code" x-ref="recovery_code" x-bind:required="showRecoveryInput"
                    autocomplete="one-time-code" x-model="recovery_code"
                    class="w-full px-[14px] py-[10px] text-[15px] font-mono bg-base-100 border border-base-content/10 rounded-lg transition-all duration-120 outline-none placeholder:text-base-content/40 focus:border-primary focus:ring-3 focus:ring-primary/20 @error('recovery_code') border-error focus:ring-error/20 @enderror"
                    placeholder="XXXXX-XXXXX" />
                @error('recovery_code')
                    <p class="text-xs text-error flex items-center gap-1"><span>⚠</span> {{ $message }}</p>
                @enderror
            </div>

            <x-app.button type="submit" class="w-full">
                {{ __('Log in') }}
            </x-app.button>

            <div class="text-center">
                <button type="button"
                    class="text-[13px] font-medium text-primary hover:text-primary/80 transition-colors"
                    @click="toggleInput()">
                    <span x-show="!showRecoveryInput">{{ __('Use a recovery code') }}</span>
                    <span x-show="showRecoveryInput">{{ __('Use an authentication code') }}</span>
                </button>
            </div>
        </form>
    </div>
</x-layouts::auth>