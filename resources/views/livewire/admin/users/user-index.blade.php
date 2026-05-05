<div class="space-y-6">
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
            <h1 class="text-2xl font-bold text-base-content">User Management</h1>
            <p class="text-sm text-base-content/60">Manage administrative users and their roles.</p>
        </div>
        <x-ui.button variant="primary" size="md" href="{{ route('admin.users.create') }}" wire:navigate>
            <x-slot:icon>
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                </svg>
            </x-slot:icon>
            {{ __('Invite User') }}
        </x-ui.button>
    </div>

    <x-ui.table 
        :search="true" 
        wire:model.live.debounce.300ms="search" 
        placeholder="Search by name, email or phone..."
    >
        <x-slot name="filters">
            <select wire:model.live="role" class="select select-sm border-base-content/10 focus:border-dp-rose focus:ring-dp-rose/20 rounded-lg text-sm bg-white">
                <option value="">All Roles</option>
                @foreach($roles as $r)
                    <option value="{{ $r->slug }}">{{ $r->name }}</option>
                @endforeach
            </select>

            <select wire:model.live="status" class="select select-sm border-base-content/10 focus:border-dp-rose focus:ring-dp-rose/20 rounded-lg text-sm bg-white">
                <option value="">All Status</option>
                <option value="1">Active</option>
                <option value="0">Inactive</option>
            </select>
        </x-slot>

        <x-slot name="header">
            <x-ui.table.th sortable wire:click="sortBy('name')" :direction="$sortField === 'name' ? $sortDirection : null">User</x-ui.table.th>
            <x-ui.table.th sortable wire:click="sortBy('phone')" :direction="$sortField === 'phone' ? $sortDirection : null">Contact</x-ui.table.th>
            <x-ui.table.th>Roles</x-ui.table.th>
            <x-ui.table.th sortable wire:click="sortBy('is_active')" :direction="$sortField === 'is_active' ? $sortDirection : null">Status</x-ui.table.th>
            <x-ui.table.th sortable wire:click="sortBy('last_login_at')" :direction="$sortField === 'last_login_at' ? $sortDirection : null">Last Login</x-ui.table.th>
            <x-ui.table.th class="text-right">Actions</x-ui.table.th>
        </x-slot>

        @forelse($users as $user)
            <x-ui.table.row wire:key="user-{{ $user->id }}">
                <x-ui.table.td>
                    <div class="flex items-center gap-3">
                        <div class="size-8 bg-primary-soft text-primary rounded-full flex items-center justify-center font-bold text-xs">
                            {{ $user->initials() }}
                        </div>
                        <div>
                            <div class="font-bold text-base-content">{{ $user->name }}</div>
                            <div class="text-xs text-base-content/60">{{ $user->email }}</div>
                        </div>
                    </div>
                </x-ui.table.td>
                <x-ui.table.td>
                    <div class="text-[13px] font-medium text-base-content">{{ $user->phone }}</div>
                </x-ui.table.td>
                <x-ui.table.td>
                    <div class="flex flex-wrap gap-1">
                        @foreach($user->roles as $role)
                            <span class="px-2.5 py-1 bg-primary/10 text-primary border border-primary/20 rounded-full text-[10px] font-black uppercase tracking-widest">
                                {{ $role->name }}
                            </span>
                        @endforeach
                    </div>
                </x-ui.table.td>
                <x-ui.table.td>
                    @if($user->invitation_sent_at && ! $user->invitation_accepted_at)
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-[#F96015]/10 text-[#F96015]">
                            <span class="size-1.5 rounded-full bg-[#F96015]"></span>
                            Pending Invite
                        </span>
                    @elseif($user->is_active)
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-success/10 text-dp-success">
                            <span class="size-1.5 rounded-full bg-success"></span>
                            Active
                        </span>
                    @else
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-error/10 text-dp-error">
                            <span class="size-1.5 rounded-full bg-error"></span>
                            Inactive
                        </span>
                    @endif
                    @if($user->must_change_password)
                        <span class="mt-1 inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-bold bg-[#FFC926]/20 text-[#B08A00]">
                            Temp Password
                        </span>
                    @endif
                </x-ui.table.td>
                <x-ui.table.td>
                    <span class="text-[13px] text-base-content/60 font-medium">
                        {{ $user->last_login_at?->diffForHumans() ?? 'Never' }}
                    </span>
                </x-ui.table.td>
                <x-ui.table.td class="text-right">
                    <div class="flex justify-end gap-1.5 pr-2">
                        <a href="{{ route('admin.users.show', $user->uuid) }}" 
                           class="p-1.5 text-base-content/40 hover:text-primary hover:bg-primary/10 rounded-lg transition-all" 
                           title="View Profile">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.183.052.158.052.338 0 .497-1.39 4.176-5.325 7.183-9.963 7.183-4.638 0-8.573-3.007-9.963-7.183Z" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                            </svg>
                        </a>

                        <a href="{{ route('admin.users.edit', $user->uuid) }}" 
                           class="p-1.5 text-base-content/40 hover:text-dp-rose hover:bg-dp-rose/10 rounded-lg transition-all" 
                           title="Edit User">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
                            </svg>
                        </a>

                        @if($user->invitation_sent_at && ! $user->invitation_accepted_at)
                            <button wire:click="confirmResendInvite({{ $user->id }})"
                                    class="p-1.5 text-[#F96015]/50 hover:text-[#F96015] hover:bg-[#F96015]/10 rounded-lg transition-all"
                                    title="Resend Invitation">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 0 1-2.25 2.25h-15a2.25 2.25 0 0 1-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25m19.5 0v.243a2.25 2.25 0 0 1-1.07 1.916l-7.5 4.615a2.25 2.25 0 0 1-2.36 0L3.32 8.91a2.25 2.25 0 0 1-1.07-1.916V6.75" />
                                </svg>
                            </button>
                        @endif

                        @if($user->id !== auth()->id())
                            <button wire:click="startAction({{ $user->id }}, 'toggleStatus')" 
                                    class="p-1.5 {{ $user->is_active ? 'text-warning/60 hover:text-warning hover:bg-warning/10' : 'text-success/60 hover:text-success hover:bg-success/10' }} rounded-lg transition-all" 
                                    title="{{ $user->is_active ? 'Disable Account' : 'Enable Account' }}">
                                @if($user->is_active)
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5"><path stroke-linecap="round" stroke-linejoin="round" d="M18.364 18.364A9 9 0 0 0 5.636 5.636m12.728 12.728A9 9 0 0 1 5.636 5.636m12.728 12.728L5.636 5.636" /></svg>
                                @else
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" /></svg>
                                @endif
                            </button>

                            @if(auth()->user()->hasRole('super_admin'))
                                <button wire:click="startAction({{ $user->id }}, 'delete')" 
                                        class="p-1.5 text-error/40 hover:text-error hover:bg-error/10 rounded-lg transition-all" 
                                        title="Delete User">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.34 6m-4.77 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                                    </svg>
                                </button>
                            @endif
                        @endif
                    </div>
                </x-ui.table.td>
            </x-ui.table.row>
        @empty
            <x-ui.table.empty 
                colspan="6" 
                title="No users found" 
                description="Try adjusting your search or filters."
            />
        @endforelse

        <x-slot name="pagination">
            {{ $users->links() }}
        </x-slot>
    </x-ui.table>

    <!-- Resend Invite Modal -->
    <x-ui.modal wire:model="showResendModal" maxWidth="sm">
        <div class="p-6">
            <div class="flex items-center gap-4 mb-4">
                <div class="w-10 h-10 rounded-full bg-[#F96015]/10 flex items-center justify-center shrink-0">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5 text-[#F96015]">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 0 1-2.25 2.25h-15a2.25 2.25 0 0 1-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25m19.5 0v.243a2.25 2.25 0 0 1-1.07 1.916l-7.5 4.615a2.25 2.25 0 0 1-2.36 0L3.32 8.91a2.25 2.25 0 0 1-1.07-1.916V6.75" />
                    </svg>
                </div>
                <h3 class="text-[17px] font-bold text-base-content">Resend Invitation</h3>
            </div>
            <p class="text-[13px] text-base-content/70 mb-1">
                A new invitation email with fresh credentials will be sent to
            </p>
            <p class="text-[14px] font-semibold text-base-content mb-4">{{ $resendTarget?->email }}</p>
            <p class="text-[12px] text-base-content/50 mb-6">
                The previous invitation link will be invalidated and a new temporary password will be generated.
            </p>
            <div class="flex justify-end gap-3">
                <button type="button" wire:click="$set('showResendModal', false)"
                    class="px-4 py-2 text-[13px] font-semibold text-base-content/60 hover:text-base-content rounded-lg hover:bg-base-200 transition-colors">
                    Cancel
                </button>
                <button type="button" wire:click="resendInvite" wire:loading.attr="disabled"
                    class="px-4 py-2 bg-[#F96015] text-white text-[13px] font-bold rounded-lg hover:brightness-105 transition-all disabled:opacity-50 flex items-center gap-2">
                    <span wire:loading.remove wire:target="resendInvite">Resend Invitation</span>
                    <span wire:loading wire:target="resendInvite" class="flex items-center gap-2">
                        <span class="loading loading-spinner loading-xs"></span>
                        Sending...
                    </span>
                </button>
            </div>
        </div>
    </x-ui.modal>

    <!-- Confirmation Modal -->
    <x-ui.modal wire:model="showConfirmationModal" maxWidth="sm">
        <div class="p-6">
            <h3 class="text-lg font-bold text-base-content mb-4">
                Confirm {{ $sensitiveAction === 'delete' ? 'Deletion' : 'Status Change' }}
            </h3>
            <p class="text-sm text-base-content/70 mb-4">
                Are you sure you want to {{ $sensitiveAction === 'delete' ? 'delete' : ($actionTarget?->is_active ? 'disable' : 'enable') }} <strong>{{ $actionTarget?->name }}</strong>?
                This action requires your password.
            </p>
            
            <form wire:submit.prevent="executeSensitiveAction" class="space-y-4">
                <x-ui.input 
                    type="password" 
                    wire:model="confirmationPassword" 
                    placeholder="Enter your password" 
                    required 
                    autocomplete="current-password"
                />
                
                <div class="flex justify-between gap-3 mt-6">
                    <button type="button" wire:click="$set('showConfirmationModal', false)" class="btn btn-ghost px-6 shadow-none">
                        Cancel
                    </button>
                    <button type="submit" class="btn {{ $sensitiveAction === 'delete' ? 'btn-error text-white' : 'btn-warning text-black' }} px-6">
                        Confirm Action
                    </button>
                </div>
            </form>
        </div>
    </x-ui.modal>
</div>
