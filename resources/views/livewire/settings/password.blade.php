<section>
    <x-settings.layout :title="__('Update Password')" :description="__('Ensure your account is using a long, random password to stay secure')">
        <div class="max-w-2xl">
            <form wire:submit="updatePassword">
                <div class="bg-white border border-base-content/5 rounded-xl shadow-sm overflow-hidden">
                    <div class="px-6 py-4 border-b border-base-content/5">
                        <p class="text-[11px] font-bold uppercase tracking-widest text-base-content/50">{{ __('Change Password') }}</p>
                    </div>
                    <div class="p-6 space-y-5">
                        <div>
                            <label class="text-[11px] font-bold uppercase tracking-widest text-base-content/50 block mb-1.5">{{ __('Current Password') }}</label>
                            <x-ui.input wire:model="current_password" type="password" required placeholder="••••••••" />
                            @error('current_password') <p class="text-[12px] text-error font-bold mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div class="border-t border-base-content/5 pt-5">
                            <label class="text-[11px] font-bold uppercase tracking-widest text-base-content/50 block mb-1.5">{{ __('New Password') }}</label>
                            <x-ui.input wire:model="password" type="password" required placeholder="••••••••" />
                            @error('password') <p class="text-[12px] text-error font-bold mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="text-[11px] font-bold uppercase tracking-widest text-base-content/50 block mb-1.5">{{ __('Confirm New Password') }}</label>
                            <x-ui.input wire:model="password_confirmation" type="password" required placeholder="••••••••" />
                            @error('password_confirmation') <p class="text-[12px] text-error font-bold mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>
                    <div class="px-6 py-4 border-t border-base-content/5 bg-base-200/30 flex items-center justify-between gap-4">
                        <x-action-message class="text-[12px] font-bold text-success flex items-center gap-1.5" on="password-updated">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            {{ __('Password updated') }}
                        </x-action-message>
                        <div></div>
                        <button type="submit" wire:loading.attr="disabled"
                            class="inline-flex items-center gap-2 px-6 py-2.5 bg-primary text-white rounded-lg font-bold text-[13px] hover:bg-primary/90 transition-colors disabled:opacity-50">
                            <span wire:loading.remove wire:target="updatePassword">{{ __('Update Password') }}</span>
                            <span wire:loading wire:target="updatePassword" class="flex items-center gap-2">
                                <span class="loading loading-spinner loading-xs"></span>
                                {{ __('Updating...') }}
                            </span>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </x-settings.layout>
</section>
