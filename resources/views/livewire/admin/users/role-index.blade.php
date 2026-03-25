<div class="space-y-8 pb-12">
    <!-- Header Section -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-end gap-6">
        <div>
            <h1 class="text-4xl font-extrabold tracking-tight text-base-content leading-tight">
                {{ __('Roles & Permissions') }}
            </h1>
            <p class="text-base-content/60 font-medium mt-2 max-w-2xl">
                {{ __('Manage administrative roles and configure granular system access across the platform.') }}
            </p>
        </div>
        <x-ui.button 
            variant="primary"
            wire:click="$set('showCreateModal', true)"
            class="rounded-lg px-6 py-4 shadow-xl active:scale-95"
        >
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4" />
            </svg>
            {{ __('Add New Role') }}
        </x-ui.button>
    </div>

    <!-- Main Content Grid -->
    <div class="grid grid-cols-1 xl:grid-cols-12 gap-8 items-start">
        
        <!-- Sidebar Navigation: Roles -->
        <div class="xl:col-span-3 space-y-4">
            <h3 class="text-[10px] font-black uppercase tracking-[0.25em] text-base-content/30 px-4">
                {{ __('System Roles') }}
            </h3>
            
            <div class="bg-white border border-base-content/5 rounded-lg shadow-sm overflow-hidden p-2 space-y-1">
                @foreach($roles as $role)
                    <button 
                        wire:click="selectRole({{ $role->id }})"
                        @class([
                            'w-full text-left px-5 py-4 rounded-lg transition-all flex items-center justify-between group relative overflow-hidden',
                            'bg-primary text-white shadow-lg shadow-primary/20' => $selectedRole?->id === $role->id,
                            'hover:bg-base-200 text-base-content/70 hover:text-base-content' => $selectedRole?->id !== $role->id
                        ])
                    >
                        <div class="flex flex-col relative z-10">
                            <span class="text-[15px] font-bold tracking-tight">{{ $role->name }}</span>
                            <span @class([
                                'text-[11px] font-medium leading-tight mt-0.5 opacity-60',
                                'text-white' => $selectedRole?->id === $role->id,
                                'text-base-content/50' => $selectedRole?->id !== $role->id
                            ])>
                                {{ Str::limit($role->description, 40) }}
                            </span>
                        </div>
                        @if($selectedRole?->id === $role->id)
                            <div class="w-1.5 h-1.5 rounded-full bg-white relative z-10"></div>
                        @endif
                    </button>
                @endforeach
            </div>
        </div>

        <!-- Permissions Configuration Area -->
        <div class="xl:col-span-9">
            @if($selectedRole)
                <div class="bg-white border border-base-content/5 rounded-lg shadow-sm overflow-hidden flex flex-col">
                    <!-- Role Detail Header -->
                    <div class="p-8 border-b border-base-content/5 bg-base-200/20 flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
                        <div class="space-y-2">
                            <div class="flex items-center gap-3">
                                <h2 class="text-2xl font-extrabold text-base-content tracking-tight">{{ $selectedRole->name }}</h2>
                                @if($selectedRole->slug !== 'super_admin')
                                    <x-badge variant="danger" outline class="text-[9px] font-black uppercase tracking-widest px-2 py-0.5">
                                        {{ __('Custom Role') }}
                                    </x-badge>
                                @else
                                    <x-badge variant="primary" class="text-[9px] font-black uppercase tracking-widest px-2 py-0.5 shadow-sm shadow-primary/30">
                                        {{ __('System Root') }}
                                    </x-badge>
                                @endif
                            </div>
                            <p class="text-[13px] text-base-content/60 font-semibold max-w-xl leading-relaxed">{{ $selectedRole->description }}</p>
                        </div>
                        
                        <div class="flex items-center gap-3">
                            @if($selectedRole->slug !== 'super_admin')
                                <x-ui.button 
                                    variant="ghost" 
                                    wire:click="deleteRole({{ $selectedRole->id }})" 
                                    wire:confirm="{{ __('Are you sure you want to delete this role?') }}"
                                    class="text-error border-error/20 hover:bg-error/10 rounded-lg"
                                >
                                    {{ __('Delete Role') }}
                                </x-ui.button>
                            @endif
                            <x-ui.button 
                                variant="primary"
                                wire:click="savePermissions"
                                class="rounded-lg px-8 shadow-lg shadow-primary/20"
                            >
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
                                </svg>
                                {{ __('Save Changes') }}
                            </x-ui.button>
                        </div>
                    </div>

                    <!-- Permissions Grid -->
                    <div class="p-8 space-y-12">
                        @foreach($permissionsGrouped as $category => $categoryPermissions)
                            <div class="space-y-6">
                                <div class="flex items-center gap-4">
                                    <h3 class="text-[11px] font-black uppercase tracking-[0.3em] text-base-content/20">{{ $category }}</h3>
                                    <div class="h-px flex-1 bg-base-content/5"></div>
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                                    @foreach($categoryPermissions as $permission)
                                        <label @class([
                                            'relative flex items-start gap-4 p-5 border rounded-lg transition-all cursor-pointer group',
                                            'border-primary/20 bg-primary/5 ring-1 ring-primary/10 shadow-sm' => in_array($permission->id, $rolePermissions),
                                            'border-base-content/5 bg-white hover:border-primary/20 hover:bg-base-200/30' => !in_array($permission->id, $rolePermissions)
                                        ])>
                                            <div class="flex items-center h-5 mt-1">
                                                <input 
                                                    type="checkbox" 
                                                    value="{{ $permission->id }}" 
                                                    wire:model="rolePermissions"
                                                    class="checkbox checkbox-primary checkbox-sm rounded-lg border-base-content/20 cursor-pointer"
                                                >
                                            </div>
                                            <div class="flex flex-col">
                                                <span @class([
                                                    'text-[14px] font-bold transition-colors',
                                                    'text-primary' => in_array($permission->id, $rolePermissions),
                                                    'text-base-content group-hover:text-primary' => !in_array($permission->id, $rolePermissions)
                                                ])>
                                                    {{ $permission->name }}
                                                </span>
                                                <span class="text-[11px] text-base-content/50 font-medium leading-relaxed mt-1">
                                                    {{ $permission->description }}
                                                </span>
                                            </div>
                                        </label>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @else
                <!-- Empty State -->
                <div class="bg-white border border-base-content/5 rounded-lg shadow-sm p-20 text-center border-dashed">
                    <div class="w-24 h-24 bg-base-200 rounded-full flex items-center justify-center mx-auto mb-8">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-10 h-10 text-base-content/20" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                        </svg>
                    </div>
                    <h3 class="text-2xl font-extrabold text-base-content tracking-tight">{{ __('Select a Role to Manage') }}</h3>
                    <p class="text-base-content/40 text-[14px] font-medium max-w-sm mx-auto mt-3 leading-relaxed">
                        {{ __('Choose a system role from the sidebar to view and modify its access permissions.') }}
                    </p>
                </div>
            @endif
        </div>
    </div>

    <!-- Create Role Modal -->
    <x-ui.modal wire:model="showCreateModal" title="Create New Role">
        <div class="space-y-8 py-2">
            <div class="space-y-6">
                <div class="space-y-2">
                    <label for="new_name" class="text-[10px] font-black uppercase tracking-widest text-base-content/40 px-1">{{ __('Role Name') }}</label>
                    <input 
                        type="text" 
                        id="new_name" 
                        wire:model="name" 
                        class="w-full px-5 py-4 bg-base-200 border border-base-content/5 rounded-lg focus:ring-4 focus:ring-primary/10 focus:border-primary transition-all outline-none font-bold text-[15px]" 
                        placeholder="e.g. Content Manager"
                    >
                    @error('name') <span class="text-[11px] text-error font-bold px-1 mt-1 block">{{ $message }}</span> @enderror
                </div>

                <div class="space-y-2">
                    <label for="new_description" class="text-[10px] font-black uppercase tracking-widest text-base-content/40 px-1">{{ __('Description') }}</label>
                    <textarea 
                        id="new_description" 
                        wire:model="description" 
                        rows="3" 
                        class="w-full px-5 py-4 bg-base-200 border border-base-content/5 rounded-lg focus:ring-4 focus:ring-primary/10 focus:border-primary transition-all outline-none font-bold text-[15px] resize-none leading-relaxed" 
                        placeholder="What is this role responsible for?"
                    ></textarea>
                    @error('description') <span class="text-[11px] text-error font-bold px-1 mt-1 block">{{ $message }}</span> @enderror
                </div>
            </div>

            <div class="flex items-center gap-3">
                <x-ui.button variant="primary" wire:click="createRole" class="flex-1 rounded-lg py-4 shadow-lg shadow-primary/20">
                    {{ __('Create Role') }}
                </x-ui.button>
                <x-ui.button variant="ghost" wire:click="$set('showCreateModal', false)" class="rounded-lg border border-base-content/10">
                    {{ __('Cancel') }}
                </x-ui.button>
            </div>
        </div>
    </x-ui.modal>
</div>
