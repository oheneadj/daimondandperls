<section class="max-w-2xl">
    <x-settings.layout :title="__('Update password')" :description="__('Ensure your account is using a long, random password to stay secure')">
        <form method="POST" wire:submit="updatePassword" class="space-y-8 mt-6">
            <!-- Current Password -->
            <div class="space-y-2.5">
                <label class=" text-[11px] font-bold uppercase tracking-[0.2em] text-base-content/60 ml-1">{{ __('Current Protocol') }}</label>
                <x-ui.input wire:model="current_password" type="password" required placeholder="••••••••" class="rounded-2xl h-14 bg-base-100 border-base-content/10/60" />
                @error('current_password') <p class=" text-[11px] text-error font-bold mt-1.5 ml-1">{{ $message }}</p> @enderror
            </div>

            <!-- New Password -->
            <div class="space-y-2.5">
                <label class=" text-[11px] font-bold uppercase tracking-[0.2em] text-base-content/60 ml-1">{{ __('New Access Key') }}</label>
                <x-ui.input wire:model="password" type="password" required placeholder="••••••••" class="rounded-2xl h-14 bg-base-100 border-base-content/10/60" />
                @error('password') <p class=" text-[11px] text-error font-bold mt-1.5 ml-1">{{ $message }}</p> @enderror
            </div>

            <!-- Confirm Password -->
            <div class="space-y-2.5">
                <label class=" text-[11px] font-bold uppercase tracking-[0.2em] text-base-content/60 ml-1">{{ __('Confirm Access Key') }}</label>
                <x-ui.input wire:model="password_confirmation" type="password" required placeholder="••••••••" class="rounded-2xl h-14 bg-base-100 border-base-content/10/60" />
                @error('password_confirmation') <p class=" text-[11px] text-error font-bold mt-1.5 ml-1">{{ $message }}</p> @enderror
            </div>

            <div class="flex items-center gap-6 pt-10 border-t border-base-content/10/50">
                <x-ui.button type="submit" variant="primary" size="lg" class="px-10 h-14 rounded-2xl shadow-dp-lg  text-[14px] uppercase tracking-widest">
                    {{ __('Finalize Update') }}
                </x-ui.button>

                <x-action-message class=" text-[13px] font-bold text-secondary bg-secondary-soft px-4 py-2 rounded-xl border border-dp-green/10" on="password-updated">
                    {{ __('Protocol Verified') }}
                </x-action-message>
            </div>
        </form>
    </x-settings.layout>
</section>