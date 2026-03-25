<section class="max-w-2xl">
    <x-settings.layout :title="__('Profile')" :description="__('Update your name and email address')">
        <form wire:submit="updateProfileInformation" class="space-y-8 mt-6">
            <!-- Name -->
            <div class="space-y-2.5">
                <label class=" text-[11px] font-bold uppercase tracking-[0.2em] text-base-content/60 ml-1">{{ __('Full Legal Name') }}</label>
                <x-ui.input wire:model="name" type="text" required autofocus placeholder="e.g. Ohene Adjei" class="rounded-2xl h-14 bg-base-100 border-base-content/10/60" />
                @error('name') <p class=" text-[11px] text-error font-bold mt-1.5 ml-1">{{ $message }}</p> @enderror
            </div>

            <!-- Email -->
            <div class="space-y-2.5">
                <label class=" text-[11px] font-bold uppercase tracking-[0.2em] text-base-content/60 ml-1">{{ __('Communication Email') }}</label>
                <x-ui.input wire:model="email" type="email" required placeholder="client@example.com" class="rounded-2xl h-14 bg-base-100 border-base-content/10/60" />
                @error('email') <p class=" text-[11px] text-error font-bold mt-1.5 ml-1">{{ $message }}</p> @enderror

                @if ($this->hasUnverifiedEmail)
                    <div
                        class="mt-4 p-4 rounded-xl bg-warning/10 border border-warning/20 text-warning flex flex-col gap-2">
                        <div class="flex items-center gap-2 font-bold text-sm">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                            </svg>
                            {{ __('Your email address is unverified.') }}
                        </div>
                        <x-ui.button wire:click.prevent="resendVerificationNotification" variant="ghost" size="sm" class="justify-start !px-0 hover:bg-transparent underline decoration-warning/30 font-bold text-warning">
                            {{ __('Click here to re-send the verification email.') }}
                        </x-ui.button>

                        @if (session('status') === 'verification-link-sent')
                            <div class="text-xs font-bold bg-success/20 text-success p-2 rounded-lg mt-2 animate-pulse">
                                {{ __('A new verification link has been sent to your email address.') }}
                            </div>
                        @endif
                    </div>
                @endif
            </div>

            <div class="flex items-center gap-6 pt-10 border-t border-base-content/10/50">
                <x-ui.button type="submit" variant="primary" class="px-10 h-14 rounded-2xl shadow-dp-lg hover:shadow-xl transition-all  text-[14px] uppercase tracking-widest">
                    {{ __('Finalize Refinement') }}
                </x-ui.button>

                <x-action-message class=" text-[13px] font-bold text-secondary bg-secondary-soft px-4 py-2 rounded-xl border border-dp-green/10" on="profile-updated">
                    {{ __('Profile Refined') }}
                </x-action-message>
            </div>
        </form>

        @if ($this->showDeleteUser)
            <div class="mt-20 border-t-2 border-dashed border-error/20 pt-10">
                <div class="bg-error/5 rounded-2xl p-8 border border-error/10">
                    <livewire:settings.delete-user-form />
                </div>
            </div>
        @endif
    </x-settings.layout>
</section>