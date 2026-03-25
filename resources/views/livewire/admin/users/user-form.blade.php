<div class="max-w-3xl mx-auto space-y-6">
    <div class="flex items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-base-content">{{ $user ? 'Edit User' : 'Invite New User' }}</h1>
            <p class="text-sm text-base-content/60">Fill in the details to {{ $user ? 'update' : 'invite' }} an administrative user.</p>
        </div>
        <x-ui.button variant="black" class="border-0" size="sm" href="{{ route('admin.users.index') }}" wire:navigate>
            <x-slot:icon>
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
            </x-slot:icon>
            Back
        </x-ui.button>
    </div>

    <form wire:submit="save" class="bg-white border border-base-content/10 rounded-lg shadow-sm overflow-hidden">
        <div class="p-8 space-y-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="space-y-2">
                    <label for="name" class="text-sm font-bold text-base-content">Full Name</label>
                    <input type="text" id="name" wire:model="name" class="w-full px-4 py-3 bg-base-200 border border-base-content/10 rounded-lg focus:ring-2 focus:ring-dp-rose/20 focus:border-dp-rose transition-all outline-none font-medium" placeholder="e.g. John Doe">
                    @error('name') <span class="text-xs text-error font-medium">{{ $message }}</span> @enderror
                </div>

                <div class="space-y-2">
                    <label for="email" class="text-sm font-bold text-base-content">Email Address</label>
                    <input type="email" id="email" wire:model="email" class="w-full px-4 py-3 bg-base-200 border border-base-content/10 rounded-lg focus:ring-2 focus:ring-dp-rose/20 focus:border-dp-rose transition-all outline-none font-medium" placeholder="john@example.com">
                    @error('email') <span class="text-xs text-error font-medium">{{ $message }}</span> @enderror
                </div>

                <div class="space-y-2">
                    <label for="phone" class="text-sm font-bold text-base-content">Phone Number</label>
                    <input type="text" id="phone" wire:model="phone" class="w-full px-4 py-3 bg-base-200 border border-base-content/10 rounded-lg focus:ring-2 focus:ring-dp-rose/20 focus:border-dp-rose transition-all outline-none font-medium" placeholder="e.g. +233 24 000 0000">
                    @error('phone') <span class="text-xs text-error font-medium">{{ $message }}</span> @enderror
                </div>

                <div class="space-y-2">
                    <label class="text-sm font-bold text-base-content block mb-2">Account Status</label>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" wire:model="is_active" class="sr-only peer">
                        <div class="w-11 h-6 border-base-content/10 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-success"></div>
                        <span class="ms-3 text-sm font-medium text-base-content/60">Active Account</span>
                    </label>
                </div>
            </div>

            <div class="space-y-4 pt-4 border-t border-base-content/10">
                <h3 class="text-sm font-bold text-base-content">Assign Role</h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                    @foreach($roles as $role)
                        <label class="flex items-start gap-3 p-4 border border-base-content/10 rounded-lg hover:bg-base-200 transition-all cursor-pointer group {{ $selectedRole == $role->id ? 'border-dp-rose bg-primary-soft' : '' }}">
                            <input type="radio" name="selectedRole" value="{{ $role->id }}" wire:model="selectedRole" class="mt-1 radio radio-sm radio-secondary border-base-content/10">
                            <div>
                                <div class="text-[14px] font-bold text-base-content">{{ $role->name }}</div>
                                <div class="text-[11px] text-base-content/60 font-medium">{{ $role->description }}</div>
                            </div>
                        </label>
                    @endforeach
                </div>
                @error('selectedRole') <span class="text-xs text-error font-medium">{{ $message }}</span> @enderror
            </div>
        </div>

        <div class="p-8 border-t border-base-content/10 flex justify-end gap-3">
            <a href="{{ route('admin.users.index') }}" wire:navigate class="px-6 py-3 text-sm font-bold text-base-content/60 hover:text-base-content transition-all">
                Cancel
            </a>
            <button type="submit" class="bg-primary text-white px-8 py-3 rounded-lg font-bold hover:bg-primary-hover transition-all shadow-md flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="size-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
                {{ $user ? 'Save Changes' : 'Invite User' }}
            </button>
        </div>
    </form>
</div>
