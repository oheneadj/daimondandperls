<div class="space-y-6 pb-10">

    {{-- Page Header --}}
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-4">
        <div>
            <h1 class="text-[24px] md:text-[28px] font-semibold text-base-content leading-tight">
                {{ __('Roles & Permissions') }}
            </h1>
            <p class="text-[13px] md:text-[14px] text-base-content/50 mt-1">
                {{ __('Define what each team role can access across the platform.') }}
            </p>
        </div>
        @if($this->isSuperAdmin())
            <x-ui.button
                variant="primary"
                wire:click="$set('showCreateModal', true)"
                class="w-full md:w-auto shadow-sm"
            >
                <x-slot:icon>
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4" />
                    </svg>
                </x-slot:icon>
                {{ __('New Role') }}
            </x-ui.button>
        @endif
    </div>

    {{-- Main Layout --}}
    <div class="grid grid-cols-1 xl:grid-cols-12 gap-6 items-start">

        {{-- Roles Sidebar --}}
        <div class="xl:col-span-3 space-y-2">
            <p class="text-[10px] font-bold uppercase tracking-[0.2em] text-base-content/30 px-1">
                {{ __('System Roles') }}
            </p>

            <div class="bg-white border border-base-content/5 rounded-xl shadow-sm overflow-hidden">
                @foreach($roles as $role)
                    @php $isActive = $selectedRole?->id === $role->id; @endphp
                    <button
                        wire:click="selectRole({{ $role->id }})"
                        wire:key="role-btn-{{ $role->id }}"
                        class="w-full text-left px-4 py-3.5 flex items-center justify-between gap-3 transition-all
                            {{ $isActive ? 'bg-primary text-white' : 'hover:bg-base-200/60 text-base-content/70 hover:text-base-content' }}
                            {{ !$loop->last ? 'border-b border-base-content/5' : '' }}"
                    >
                        <div class="flex items-center gap-3 min-w-0">
                            {{-- Role icon dot --}}
                            <div class="w-8 h-8 rounded-lg flex-shrink-0 flex items-center justify-center
                                {{ $isActive ? 'bg-white/20' : 'bg-base-200' }}">
                                @if($role->slug === 'super_admin')
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 {{ $isActive ? 'text-white' : 'text-[#A31C4E]' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                                    </svg>
                                @else
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 {{ $isActive ? 'text-white' : 'text-base-content/40' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                    </svg>
                                @endif
                            </div>
                            <div class="min-w-0">
                                <p class="text-[13px] font-bold truncate {{ $isActive ? 'text-white' : 'text-base-content' }}">
                                    {{ $role->name }}
                                </p>
                                <p class="text-[11px] truncate {{ $isActive ? 'text-white/60' : 'text-base-content/40' }}">
                                    {{ $role->permissions->count() }} permissions
                                </p>
                            </div>
                        </div>
                        @if($isActive)
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 flex-shrink-0 text-white/70" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                        @endif
                    </button>
                @endforeach
            </div>

            @if(!$this->isSuperAdmin())
                <div class="flex items-start gap-2 px-1 pt-1">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 text-base-content/30 flex-shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <p class="text-[11px] text-base-content/30 leading-relaxed">
                        {{ __('Only the Super Admin can create or modify roles.') }}
                    </p>
                </div>
            @endif
        </div>

        {{-- Permissions Panel --}}
        <div class="xl:col-span-9">
            @if($selectedRole)
                @php $isSuperAdminRole = $selectedRole->slug === 'super_admin'; @endphp

                <div class="bg-white border border-base-content/5 rounded-xl shadow-sm overflow-hidden">

                    {{-- Role Header --}}
                    <div class="px-6 py-5 border-b border-base-content/5 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                        <div class="flex items-center gap-4">
                            <div class="w-11 h-11 rounded-xl flex-shrink-0 flex items-center justify-center
                                {{ $isSuperAdminRole ? 'bg-[#A31C4E]/10' : 'bg-primary/10' }}">
                                @if($isSuperAdminRole)
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-[#A31C4E]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                                    </svg>
                                @else
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                    </svg>
                                @endif
                            </div>
                            <div>
                                <div class="flex items-center gap-2 flex-wrap">
                                    <h2 class="text-[16px] font-bold text-base-content">{{ $selectedRole->name }}</h2>
                                    @if($isSuperAdminRole)
                                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[9px] font-black uppercase tracking-widest bg-[#A31C4E]/10 text-[#A31C4E] border border-[#A31C4E]/20">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-2.5 h-2.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                            </svg>
                                            System Root
                                        </span>
                                    @endif
                                </div>
                                <p class="text-[12px] text-base-content/50 mt-0.5">{{ $selectedRole->description }}</p>
                            </div>
                        </div>

                        <div class="flex items-center gap-2">
                            @if($this->isSuperAdmin() && !$isSuperAdminRole)
                                <x-ui.button
                                    variant="ghost"
                                    size="sm"
                                    wire:click="openEditModal"
                                    class="border border-base-content/10"
                                >
                                    <x-slot:icon>
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                        </svg>
                                    </x-slot:icon>
                                    {{ __('Edit') }}
                                </x-ui.button>

                                <x-ui.button
                                    variant="ghost"
                                    size="sm"
                                    wire:click="deleteRole({{ $selectedRole->id }})"
                                    wire:confirm="{{ __('Delete this role? Users assigned to it will lose these permissions.') }}"
                                    class="text-error hover:bg-error/10 border-error/20"
                                >
                                    <x-slot:icon>
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                    </x-slot:icon>
                                    {{ __('Delete') }}
                                </x-ui.button>

                                <x-ui.button
                                    variant="primary"
                                    size="sm"
                                    wire:click="savePermissions"
                                    wire:loading.attr="disabled"
                                    wire:target="savePermissions"
                                    class="shadow-sm"
                                >
                                    <x-slot:icon>
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                                        </svg>
                                    </x-slot:icon>
                                    {{ __('Save Changes') }}
                                </x-ui.button>
                            @elseif($isSuperAdminRole)
                                <div class="flex items-center gap-1.5 text-[11px] text-base-content/40 font-medium italic">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                    </svg>
                                    {{ __('All permissions locked') }}
                                </div>
                            @endif
                        </div>
                    </div>

                    {{-- Permissions Groups --}}
                    <div class="divide-y divide-base-content/5">
                        @foreach($permissionsGrouped as $category => $categoryPermissions)
                            <div class="px-6 py-5">
                                <div class="flex items-center gap-3 mb-4">
                                    <p class="text-[10px] font-black uppercase tracking-[0.25em] text-base-content/30">
                                        {{ $category }}
                                    </p>
                                    <div class="h-px flex-1 bg-base-content/5"></div>
                                    <span class="text-[10px] font-bold text-base-content/25">
                                        @php
                                            $activeCount = $isSuperAdminRole
                                                ? $categoryPermissions->count()
                                                : $categoryPermissions->filter(fn($p) => in_array($p->id, $rolePermissions))->count();
                                        @endphp
                                        {{ $activeCount }}/{{ $categoryPermissions->count() }}
                                    </span>
                                </div>

                                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3">
                                    @foreach($categoryPermissions as $permission)
                                        @php
                                            $isGranted = $isSuperAdminRole || in_array($permission->id, $rolePermissions);
                                            $isEditable = !$isSuperAdminRole && $this->isSuperAdmin();
                                        @endphp
                                        <label @class([
                                            'flex items-start gap-3 p-4 rounded-lg border transition-all group',
                                            'cursor-pointer' => $isEditable,
                                            'cursor-default' => !$isEditable,
                                            'border-primary/20 bg-primary/5' => $isGranted && !$isSuperAdminRole,
                                            'border-[#A31C4E]/20 bg-[#A31C4E]/5' => $isGranted && $isSuperAdminRole,
                                            'border-base-content/8 bg-base-100 hover:border-primary/20 hover:bg-primary/5' => !$isGranted && $isEditable,
                                            'border-base-content/5 bg-base-100' => !$isGranted && !$isEditable,
                                        ])>
                                            <div class="mt-0.5 flex-shrink-0">
                                                <input
                                                    type="checkbox"
                                                    value="{{ $permission->id }}"
                                                    @if($isEditable) wire:model="rolePermissions" @else disabled @endif
                                                    @checked($isGranted)
                                                    @class([
                                                        'checkbox checkbox-sm rounded',
                                                        'checkbox-primary cursor-pointer' => $isEditable,
                                                        'opacity-60 cursor-default' => !$isEditable,
                                                    ])
                                                >
                                            </div>
                                            <div class="min-w-0">
                                                <p @class([
                                                    'text-[13px] font-semibold leading-tight',
                                                    'text-primary' => $isGranted && !$isSuperAdminRole,
                                                    'text-[#A31C4E]' => $isGranted && $isSuperAdminRole,
                                                    'text-base-content/60' => !$isGranted,
                                                ])>
                                                    {{ $permission->name }}
                                                </p>
                                                <p class="text-[11px] text-base-content/40 mt-0.5 leading-snug">
                                                    {{ $permission->description }}
                                                </p>
                                            </div>
                                        </label>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    </div>

                    {{-- Footer save bar (sticky on scroll, non-super_admin roles only) --}}
                    @if($this->isSuperAdmin() && !$isSuperAdminRole)
                        <div class="px-6 py-4 bg-base-200/30 border-t border-base-content/5 flex items-center justify-between gap-4">
                            <p class="text-[12px] text-base-content/40 font-medium">
                                {{ __('Changes are not saved automatically.') }}
                            </p>
                            <x-ui.button
                                variant="primary"
                                size="sm"
                                wire:click="savePermissions"
                                wire:loading.attr="disabled"
                                wire:target="savePermissions"
                                class="shadow-sm"
                            >
                                <x-slot:icon>
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                                    </svg>
                                </x-slot:icon>
                                <span wire:loading.remove wire:target="savePermissions">{{ __('Save Changes') }}</span>
                                <span wire:loading wire:target="savePermissions">{{ __('Saving…') }}</span>
                            </x-ui.button>
                        </div>
                    @endif
                </div>

            @else
                {{-- Empty state --}}
                <div class="bg-white border border-base-content/5 rounded-xl shadow-sm p-16 text-center">
                    <div class="w-16 h-16 bg-base-200 rounded-2xl flex items-center justify-center mx-auto mb-5">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-7 h-7 text-base-content/20" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                        </svg>
                    </div>
                    <h3 class="text-[16px] font-bold text-base-content">{{ __('Select a role to configure') }}</h3>
                    <p class="text-[13px] text-base-content/40 mt-1 max-w-xs mx-auto">
                        {{ __('Pick a role from the left to view and adjust its permissions.') }}
                    </p>
                </div>
            @endif
        </div>
    </div>

    {{-- Edit Role Modal --}}
    @if($this->isSuperAdmin())
        <x-ui.modal wire:model="showEditModal" title="{{ __('Edit Role') }}">
            <div class="space-y-5 py-1">
                <div class="space-y-1.5">
                    <label class="text-[10px] font-black uppercase tracking-[0.2em] text-base-content/40 block">
                        {{ __('Role Name') }}
                    </label>
                    <x-ui.input
                        type="text"
                        wire:model="editName"
                        placeholder="e.g. Kitchen Manager"
                    />
                    @error('editName')
                        <p class="text-[11px] text-error font-semibold">{{ $message }}</p>
                    @enderror
                </div>

                <div class="space-y-1.5">
                    <label class="text-[10px] font-black uppercase tracking-[0.2em] text-base-content/40 block">
                        {{ __('Description') }}
                    </label>
                    <textarea
                        wire:model="editDescription"
                        rows="3"
                        placeholder="What responsibilities does this role carry?"
                        class="w-full px-4 py-3 bg-base-200 border border-base-content/5 rounded-lg text-[14px] font-medium outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary/40 transition-all resize-none"
                    ></textarea>
                    @error('editDescription')
                        <p class="text-[11px] text-error font-semibold">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex items-center gap-3 pt-2">
                    <x-ui.button
                        variant="primary"
                        wire:click="updateRole"
                        wire:loading.attr="disabled"
                        wire:target="updateRole"
                        class="flex-1 shadow-sm"
                    >
                        <span wire:loading.remove wire:target="updateRole">{{ __('Save Changes') }}</span>
                        <span wire:loading wire:target="updateRole">{{ __('Saving…') }}</span>
                    </x-ui.button>
                    <x-ui.button
                        variant="ghost"
                        wire:click="$set('showEditModal', false)"
                        class="border border-base-content/10"
                    >
                        {{ __('Cancel') }}
                    </x-ui.button>
                </div>
            </div>
        </x-ui.modal>
    @endif

    {{-- Create Role Modal --}}
    @if($this->isSuperAdmin())
        <x-ui.modal wire:model="showCreateModal" title="{{ __('Create New Role') }}">
            <div class="space-y-5 py-1">
                <div class="space-y-1.5">
                    <label class="text-[10px] font-black uppercase tracking-[0.2em] text-base-content/40 block">
                        {{ __('Role Name') }}
                    </label>
                    <x-ui.input
                        type="text"
                        wire:model="name"
                        placeholder="e.g. Kitchen Manager"
                        autofocus
                    />
                    @error('name')
                        <p class="text-[11px] text-error font-semibold">{{ $message }}</p>
                    @enderror
                </div>

                <div class="space-y-1.5">
                    <label class="text-[10px] font-black uppercase tracking-[0.2em] text-base-content/40 block">
                        {{ __('Description') }}
                    </label>
                    <textarea
                        wire:model="description"
                        rows="3"
                        placeholder="What responsibilities does this role carry?"
                        class="w-full px-4 py-3 bg-base-200 border border-base-content/5 rounded-lg text-[14px] font-medium outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary/40 transition-all resize-none"
                    ></textarea>
                    @error('description')
                        <p class="text-[11px] text-error font-semibold">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex items-center gap-3 pt-2">
                    <x-ui.button
                        variant="primary"
                        wire:click="createRole"
                        wire:loading.attr="disabled"
                        wire:target="createRole"
                        class="flex-1 shadow-sm"
                    >
                        <span wire:loading.remove wire:target="createRole">{{ __('Create Role') }}</span>
                        <span wire:loading wire:target="createRole">{{ __('Creating…') }}</span>
                    </x-ui.button>
                    <x-ui.button
                        variant="ghost"
                        wire:click="$set('showCreateModal', false)"
                        class="border border-base-content/10"
                    >
                        {{ __('Cancel') }}
                    </x-ui.button>
                </div>
            </div>
        </x-ui.modal>
    @endif
</div>
