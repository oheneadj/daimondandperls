<section class="space-y-4">
    <div>
        <p class="text-[13px] font-bold text-base-content">{{ __('Delete Account') }}</p>
        <p class="text-[12px] text-base-content/50 font-medium mt-0.5">
            {{ __('Permanently delete your account and all associated data. This cannot be undone.') }}
        </p>
    </div>

    <button type="button" onclick="delete_account_modal.showModal()"
        class="inline-flex items-center gap-2 px-5 py-2.5 bg-[#D52518]/10 text-[#D52518] border border-[#D52518]/20 rounded-lg font-bold text-[13px] hover:bg-[#D52518]/20 transition-colors">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
        {{ __('Delete My Account') }}
    </button>

    <dialog id="delete_account_modal" class="modal modal-bottom sm:modal-middle">
        <div class="modal-box bg-white border border-base-content/5 rounded-2xl p-8 flex flex-col gap-6">
            <div class="flex items-center gap-4 text-[#D52518]">
                <div class="w-12 h-12 rounded-full bg-[#D52518]/10 flex items-center justify-center shrink-0">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                </div>
                <div>
                    <h3 class="font-bold text-[16px] text-base-content">{{ __('Are you sure?') }}</h3>
                    <p class="text-[12px] text-base-content/50 font-medium mt-0.5">{{ __('This action cannot be undone.') }}</p>
                </div>
            </div>

            <p class="text-[13px] text-base-content/60 font-medium leading-relaxed">
                {{ __('Once your account is deleted, all of its resources and data will be permanently removed. Please enter your password to confirm.') }}
            </p>

            <form wire:submit="deleteUser" class="space-y-4">
                @csrf
                <div>
                    <label class="text-[11px] font-bold uppercase tracking-widest text-base-content/50 block mb-1.5">{{ __('Your Password') }}</label>
                    <x-ui.input wire:model="password" type="password" required placeholder="{{ __('Enter your password') }}" />
                    @error('password') <p class="text-[12px] text-error font-bold mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="flex items-center gap-3 pt-2">
                    <form method="dialog" class="flex-1">
                        <button class="w-full px-4 py-2.5 border border-base-content/15 rounded-lg font-bold text-[13px] text-base-content/60 hover:text-base-content hover:border-base-content/30 transition-colors">
                            {{ __('Cancel') }}
                        </button>
                    </form>
                    <button type="submit"
                        class="flex-1 px-4 py-2.5 bg-[#D52518] text-white rounded-lg font-bold text-[13px] hover:bg-[#b01e14] transition-colors">
                        {{ __('Delete Account') }}
                    </button>
                </div>
            </form>
        </div>
        <form method="dialog" class="modal-backdrop bg-neutral-900/70 backdrop-blur-sm">
            <button>close</button>
        </form>
    </dialog>
</section>
