<div>
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-semibold text-base-content mb-2">Profile Settings</h1>
        <p class="text-base-content/60 text-[15px] font-medium">Update your contact details and notification preferences.</p>
    </div>

    <div class="max-w-2xl">
        <form wire:submit="save" class="space-y-6">
            <!-- Contact Details -->
            <div class="bg-white border border-base-content/10 rounded-2xl p-6 shadow-sm">
                <h2 class="text-[11px] font-bold text-primary uppercase tracking-widest mb-6">Contact Details</h2>

                <div class="space-y-5">
                    <!-- Name -->
                    <div>
                        <label for="name" class="block text-[12px] font-bold text-base-content/60 uppercase tracking-wide mb-2">Full Name</label>
                        <input type="text" id="name" wire:model="name"
                            class="w-full bg-base-200/50 border border-base-content/10 text-[14px] rounded-xl px-4 py-3 focus:ring-2 focus:ring-primary/20 focus:border-primary/30 transition-all outline-none"
                            placeholder="Your full name">
                        @error('name') <p class="text-[12px] font-bold text-error mt-1.5">{{ $message }}</p> @enderror
                    </div>

                    <!-- Email -->
                    <div>
                        <label for="email" class="block text-[12px] font-bold text-base-content/60 uppercase tracking-wide mb-2">Email Address</label>
                        <input type="email" id="email" wire:model="email"
                            class="w-full bg-base-200/50 border border-base-content/10 text-[14px] rounded-xl px-4 py-3 focus:ring-2 focus:ring-primary/20 focus:border-primary/30 transition-all outline-none"
                            placeholder="your@email.com">
                        @error('email') <p class="text-[12px] font-bold text-error mt-1.5">{{ $message }}</p> @enderror
                    </div>

                    <!-- Phone -->
                    <div>
                        <label for="phone" class="block text-[12px] font-bold text-base-content/60 uppercase tracking-wide mb-2">Phone Number</label>
                        <input type="tel" id="phone" wire:model="phone"
                            class="w-full bg-base-200/50 border border-base-content/10 text-[14px] rounded-xl px-4 py-3 focus:ring-2 focus:ring-primary/20 focus:border-primary/30 transition-all outline-none"
                            placeholder="024XXXXXXX">
                        @error('phone') <p class="text-[12px] font-bold text-error mt-1.5">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>

            <!-- Notification Preferences -->
            <div class="bg-white border border-base-content/10 rounded-2xl p-6 shadow-sm">
                <h2 class="text-[11px] font-bold text-primary uppercase tracking-widest mb-6">Notification Preferences</h2>
                <p class="text-[13px] text-base-content/60 font-medium mb-5">How would you like to receive booking updates?</p>

                <div class="space-y-3">
                    @foreach(\App\Enums\NotificationPreference::cases() as $pref)
                        <label class="flex items-center gap-3 p-3 rounded-xl border cursor-pointer transition-all
                            {{ $notificationPreference === $pref->value ? 'border-primary/30 bg-primary/5' : 'border-base-content/10 hover:border-base-content/20' }}">
                            <input type="radio" wire:model.live="notificationPreference" value="{{ $pref->value }}"
                                class="radio radio-primary radio-sm">
                            <div>
                                <div class="text-[14px] font-semibold text-base-content">{{ $pref->name }}</div>
                                <div class="text-[12px] text-base-content/50 font-medium">
                                    @switch($pref)
                                        @case(\App\Enums\NotificationPreference::Email)
                                            Receive updates via email only
                                            @break
                                        @case(\App\Enums\NotificationPreference::Sms)
                                            Receive updates via SMS only
                                            @break
                                        @case(\App\Enums\NotificationPreference::Both)
                                            Receive updates via both email and SMS
                                            @break
                                    @endswitch
                                </div>
                            </div>
                        </label>
                    @endforeach
                </div>
                @error('notificationPreference') <p class="text-[12px] font-bold text-error mt-2">{{ $message }}</p> @enderror
            </div>

            <!-- Save Button -->
            <div class="flex justify-end">
                <button type="submit" wire:loading.attr="disabled" wire:target="save"
                    class="inline-flex items-center gap-2 px-8 py-3 bg-primary text-white rounded-xl font-semibold text-[14px] hover:bg-primary/90 transition-colors shadow-sm disabled:opacity-50">
                    <span wire:loading.remove wire:target="save">Save Changes</span>
                    <span wire:loading wire:target="save">Saving...</span>
                </button>
            </div>
        </form>
    </div>
</div>
