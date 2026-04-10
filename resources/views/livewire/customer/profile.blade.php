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
                    <x-app.input
                        name="name"
                        type="text"
                        label="Full Name"
                        wire:model="name"
                        placeholder="Your full name"
                    />

                    <!-- Email -->
                    <x-app.input
                        name="email"
                        type="email"
                        label="Email Address"
                        wire:model="email"
                        placeholder="your@email.com"
                    />

                    <!-- Phone -->
                    <x-app.input
                        name="phone"
                        type="tel"
                        label="Phone Number"
                        wire:model="phone"
                        placeholder="024XXXXXXX"
                    />
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
