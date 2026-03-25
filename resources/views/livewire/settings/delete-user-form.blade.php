<section class="space-y-6">
    <div class="flex flex-col gap-2">
        <h3 class="text-xl font-bold text-error">{{ __('Delete account') }}</h3>
        <p class="text-base-content/60 text-sm italic">
            {{ __('Delete your account and all of its resources permanently.') }}</p>
    </div>

    <x-ui.button type="button" variant="danger" outline size="sm" class="rounded-xl font-bold" onclick="delete_account_modal.showModal()">
        {{ __('Delete account') }}
    </x-ui.button>

    <dialog id="delete_account_modal" class="modal modal-bottom sm:modal-middle">
        <div class="modal-box bg-base-100 border border-base-content/5 p-8 flex flex-col gap-6">
            <div class="flex flex-col gap-2">
                <h3 class="font-black text-2xl text-error">{{ __('Are you sure?') }}</h3>
                <p class="text-base-content/60 leading-relaxed italic">
                    {{ __('Once your account is deleted, all of its resources and data will be permanently deleted. Please enter your password to confirm you would like to permanently delete your account.') }}
                </p>
            </div>

            <form method="POST" wire:submit="deleteUser" class="flex flex-col gap-6">
                @csrf
                <div class="form-control w-full">
                    <label class="label">
                        <span
                            class="label-text font-bold uppercase tracking-wider text-base-content/50 text-xs">{{ __('Password') }}</span>
                    </label>
                    <input wire:model="password" type="password" required placeholder="{{ __('Enter your password') }}"
                        class="input input-error input-bordered w-full" />
                    @error('password')
                        <label class="label font-medium text-error text-xs"><span>{{ $message }}</span></label>
                    @enderror
                </div>

                <div class="modal-action flex gap-3">
                    <form method="dialog">
                        <x-ui.button variant="ghost" class="rounded-xl font-bold uppercase tracking-widest text-[10px]">
                            {{ __('Cancel') }}
                        </x-ui.button>
                    </form>
                    <x-ui.button type="submit" variant="danger" size="sm" class="rounded-xl font-bold uppercase tracking-widest text-[10px] px-8">
                        {{ __('Permanently Delete') }}
                    </x-ui.button>
                </div>
            </form>
        </div>
        <form method="dialog" class="modal-backdrop bg-neutral-900/80 backdrop-blur-sm">
            <button>close</button>
        </form>
    </dialog>
</section>