<section>
    <x-settings.layout :title="__('Profile')" :description="__('Update your name and email address')">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 items-start max-w-4xl">

            {{-- Avatar sidebar --}}
            <div class="space-y-4">
                <div class="bg-white border border-base-content/5 rounded-xl p-6 flex flex-col items-center gap-4 text-center shadow-sm">
                    <div class="w-20 h-20 rounded-full bg-primary/10 flex items-center justify-center text-primary font-bold text-[28px] select-none">
                        {{ strtoupper(substr($name ?: 'A', 0, 1)) }}
                    </div>
                    <div>
                        <p class="text-[15px] font-bold text-base-content">{{ $name ?: __('Admin') }}</p>
                        <p class="text-[12px] text-base-content/40 mt-0.5">{{ $email }}</p>
                    </div>
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-[#A31C4E]/10 text-[#A31C4E] text-[11px] font-bold uppercase tracking-widest">
                        <span class="w-1.5 h-1.5 rounded-full bg-[#A31C4E]"></span>
                        {{ __('Administrator') }}
                    </span>
                </div>

                @if ($this->hasUnverifiedEmail)
                    <div class="bg-warning/10 border border-warning/20 rounded-xl p-4 space-y-2">
                        <div class="flex items-center gap-2 text-warning font-bold text-[12px]">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                            {{ __('Email unverified') }}
                        </div>
                        <button wire:click.prevent="resendVerificationNotification"
                            class="text-[12px] font-bold text-warning underline decoration-warning/40 hover:decoration-warning transition-all">
                            {{ __('Resend verification email') }}
                        </button>
                        @if (session('status') === 'verification-link-sent')
                            <p class="text-[11px] font-bold text-success">{{ __('Verification link sent!') }}</p>
                        @endif
                    </div>
                @endif
            </div>

            {{-- Forms --}}
            <div class="lg:col-span-2 space-y-6">
                <form wire:submit="updateProfileInformation">
                    <div class="bg-white border border-base-content/5 rounded-xl shadow-sm overflow-hidden">
                        <div class="px-6 py-4 border-b border-base-content/5">
                            <p class="text-[11px] font-bold uppercase tracking-widest text-base-content/50">{{ __('Personal Details') }}</p>
                        </div>
                        <div class="p-6 space-y-5">
                            <div>
                                <label class="text-[11px] font-bold uppercase tracking-widest text-base-content/50 block mb-1.5">{{ __('Full Name') }}</label>
                                <x-ui.input wire:model="name" type="text" required autofocus placeholder="Your full name" />
                                @error('name') <p class="text-[12px] text-error font-bold mt-1">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label class="text-[11px] font-bold uppercase tracking-widest text-base-content/50 block mb-1.5">{{ __('Email Address') }}</label>
                                <x-ui.input wire:model="email" type="email" required placeholder="admin@example.com" />
                                @error('email') <p class="text-[12px] text-error font-bold mt-1">{{ $message }}</p> @enderror
                            </div>
                        </div>
                        <div class="px-6 py-4 border-t border-base-content/5 bg-base-200/30 flex items-center justify-between gap-4">
                            <x-action-message class="text-[12px] font-bold text-success flex items-center gap-1.5" on="profile-updated">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                {{ __('Saved') }}
                            </x-action-message>
                            <div></div>
                            <button type="submit" wire:loading.attr="disabled"
                                class="inline-flex items-center gap-2 px-6 py-2.5 bg-primary text-white rounded-lg font-bold text-[13px] hover:bg-primary/90 transition-colors disabled:opacity-50">
                                <span wire:loading.remove wire:target="updateProfileInformation">{{ __('Save Changes') }}</span>
                                <span wire:loading wire:target="updateProfileInformation" class="flex items-center gap-2">
                                    <span class="loading loading-spinner loading-xs"></span>
                                    {{ __('Saving...') }}
                                </span>
                            </button>
                        </div>
                    </div>
                </form>

            </div>
        </div>
    </x-settings.layout>
</section>
