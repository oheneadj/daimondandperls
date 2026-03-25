<x-layouts::auth :title="__('Two-factor authentication')">
    <div class="flex flex-col gap-6">
        <div class="relative w-full h-auto" x-cloak x-data="{
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
                <x-auth-header :title="__('Authentication code')" :description="__('Enter the authentication code provided by your authenticator application.')" />
            </div>

            <div x-show="showRecoveryInput">
                <x-auth-header :title="__('Recovery code')" :description="__('Please confirm access to your account by entering one of your emergency recovery codes.')" />
            </div>

            <form method="POST" action="{{ route('two-factor.login.store') }}" class="mt-6 flex flex-col gap-6">
                @csrf

                <div x-show="!showRecoveryInput" class="form-control w-full">
                    <label class="label">
                        <span
                            class="label-text font-semibold uppercase tracking-wider text-base-content/50 text-xs">{{ __('Verification Code') }}</span>
                    </label>
                    <input type="text" name="code" inputmode="numeric" pattern="[0-9]*" autocomplete="one-time-code"
                        x-model="code"
                        class="input input-primary input-bordered w-full text-center text-2xl tracking-[1em] font-mono @error('code') input-error @enderror"
                        placeholder="000000" />
                    @error('code')
                        <label class="label">
                            <span class="label-text-alt text-error font-medium">{{ $message }}</span>
                        </label>
                    @enderror
                </div>

                <div x-show="showRecoveryInput" class="form-control w-full">
                    <label class="label">
                        <span
                            class="label-text font-semibold uppercase tracking-wider text-base-content/50 text-xs">{{ __('Recovery Code') }}</span>
                    </label>
                    <input type="text" name="recovery_code" x-ref="recovery_code" x-bind:required="showRecoveryInput"
                        autocomplete="one-time-code" x-model="recovery_code"
                        class="input input-primary input-bordered w-full font-mono @error('recovery_code') input-error @enderror"
                        placeholder="XXXXX-XXXXX" />
                    @error('recovery_code')
                        <label class="label">
                            <span class="label-text-alt text-error font-medium">{{ $message }}</span>
                        </label>
                    @enderror
                </div>

                <button type="submit" class="btn btn-primary btn-block">
                    {{ __('Log in') }}
                </button>

                <div class="text-sm text-center text-base-content/60">
                    <span class="opacity-60">{{ __('or you can') }}</span>
                    <button type="button"
                        class="link link-primary font-bold decoration-primary/30 underline-offset-4 ml-1"
                        @click="toggleInput()">
                        <span x-show="!showRecoveryInput">{{ __('use a recovery code') }}</span>
                        <span x-show="showRecoveryInput">{{ __('use an authentication code') }}</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-layouts::auth>