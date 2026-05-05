<div class="space-y-6 pb-10">
    {{-- Page Header --}}
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-6">
        <div>
            <h1 class="text-[28px] font-semibold text-base-content leading-tight">
                {{ __('Customers') }}
            </h1>
            <p class="text-[14px] text-base-content/50 mt-1">{{ __('Manage and engage with your catering clientele.') }}</p>
        </div>

        <x-ui.button variant="primary" size="md" href="{{ route('admin.customers.create') }}" wire:navigate disabled>
            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
            {{ __('New Customer') }}
        </x-ui.button>
    </div>

    {{-- Stats Bar --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div class="bg-white border border-base-content/5 rounded-xl p-4 flex items-center gap-4">
            <div class="w-10 h-10 rounded-xl bg-[#F96015]/10 flex items-center justify-center">
                @include('layouts.partials.icons.user-group', ['class' => 'w-5 h-5 text-[#F96015]'])
            </div>
            <div>
                <p class="text-[20px] font-bold text-base-content">{{ number_format($stats['total']) }}</p>
                <p class="text-[10px] font-bold uppercase tracking-widest text-base-content/40">{{ __('Total Customers') }}</p>
            </div>
        </div>
        <div class="bg-white border border-base-content/5 rounded-xl p-4 flex items-center gap-4" h-full>
            <div class="w-10 h-10 rounded-xl bg-[#9ABC05]/10 flex items-center justify-center">
                @include('layouts.partials.icons.check-circle-solid', ['class' => 'w-5 h-5 text-[#9ABC05]'])
            </div>
            <div>
                <p class="text-[20px] font-bold text-base-content">{{ number_format($stats['new_this_month']) }}</p>
                <p class="text-[10px] font-bold uppercase tracking-widest text-base-content/40">{{ __('New This Month') }}</p>
            </div>
        </div>
        <div class="bg-white border border-base-content/5 rounded-xl p-4 flex items-center gap-4">
            <div class="w-10 h-10 rounded-xl bg-[#FFC926]/10 flex items-center justify-center">
                @include('layouts.partials.icons.information-circle-solid', ['class' => 'w-5 h-5 text-[#FFC926]'])
            </div>
            <div>
                <p class="text-[14px] font-bold text-base-content truncate max-w-[150px]">{{ $stats['most_active'] }}</p>
                <p class="text-[10px] font-bold uppercase tracking-widest text-base-content/40">{{ __('Most Active') }}</p>
            </div>
        </div>
    </div>

    {{-- Table --}}
    <x-ui.table search="search">
        <x-slot name="filters">
            <div class="flex flex-wrap items-center gap-3">
                <!-- Role Filter -->
                <select wire:model.live="role" class="bg-base-200 border-none text-[13px] rounded-lg px-3 py-2 focus:ring-2 focus:ring-[#F96015]/30 outline-none transition-all font-medium">
                    <option value="all">{{ __('All Roles') }}</option>
                    <option value="registered">{{ __('Registered') }}</option>
                    <option value="guest">{{ __('Guest') }}</option>
                </select>

                <!-- Status Filter -->
                <select wire:model.live="status" class="bg-base-200 border-none text-[13px] rounded-lg px-3 py-2 focus:ring-2 focus:ring-[#F96015]/30 outline-none transition-all font-medium">
                    <option value="all">{{ __('All Status') }}</option>
                    <option value="active">{{ __('Active Accounts') }}</option>
                    <option value="inactive">{{ __('Inactive Accounts') }}</option>
                </select>
            </div>
        </x-slot>

        <x-slot name="header">
            <x-ui.table.th sortable="name" :direction="$sortField === 'name' ? $sortDirection : null">{{ __('Name') }}</x-ui.table.th>
            <x-ui.table.th sortable="email" :direction="$sortField === 'email' ? $sortDirection : null">{{ __('Communications') }}</x-ui.table.th>
            <x-ui.table.th align="center">{{ __('Account Status') }}</x-ui.table.th>
            <x-ui.table.th sortable="bookings_count" :direction="$sortField === 'bookings_count' ? $sortDirection : null" align="center">{{ __('Engagement') }}</x-ui.table.th>
            <x-ui.table.th sortable="created_at" :direction="$sortField === 'created_at' ? $sortDirection : null">{{ __('Joined') }}</x-ui.table.th>
            <x-ui.table.th align="right">{{ __('Actions') }}</x-ui.table.th>
        </x-slot>

        <tbody>
            @forelse ($customers as $customer)
                <x-ui.table.row wire:key="customer-{{ $customer->id }}" class="border-b border-base-content/5 last:border-0 group">
                    <x-ui.table.td>
                        <div class="flex items-center gap-4">
                            <div class="w-10 h-10 rounded-xl bg-[#F96015]/10 text-[#F96015] flex items-center justify-center text-[14px] font-bold tracking-tighter">
                                {{ substr($customer->name, 0, 2) }}
                            </div>
                            <div class="flex flex-col min-w-0">
                                <a href="{{ route('admin.customers.show', $customer) }}" wire:navigate class="text-[13px] font-semibold text-base-content hover:text-[#F96015] transition-colors truncate">
                                    {{ $customer->name }}
                                </a>
                                <span class="text-[11px] text-base-content/40">{{ __('Personal Record') }}</span>
                            </div>
                        </div>
                    </x-ui.table.td>

                    <x-ui.table.td>
                        <div class="flex flex-col gap-1">
                            <div class="flex items-center gap-2">
                                <span class="text-[12px] text-base-content font-bold">{{ $customer->email }}</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <span class="text-[11px] text-base-content/40">{{ $customer->phone }}</span>
                            </div>
                        </div>
                    </x-ui.table.td>

                    <x-ui.table.td align="center">
                        @if ($customer->user_id)
                            <x-ui.badge type="info" dot class="font-bold text-[10px] uppercase tracking-widest">
                                {{ __('Registered') }}
                            </x-ui.badge>
                        @else
                            <x-ui.badge type="neutral" dot class="font-bold text-white text-[10px] uppercase tracking-widest">
                                {{ __('Guest') }}
                            </x-ui.badge>
                        @endif
                    </x-ui.table.td>

                    <x-ui.table.td align="center">
                        <x-ui.badge type="warning" dot class="font-bold text-[10px] uppercase tracking-widest">
                            {{ $customer->bookings_count }} {{ __('Bookings') }}
                        </x-ui.badge>
                    </x-ui.table.td>

                    <x-ui.table.td>
                        <div class="flex items-center gap-2">
                            <div class="w-6 h-6 rounded-md bg-base-200 flex items-center justify-center shrink-0">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 text-base-content/40" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            </div>
                            <span class="text-[12px] text-base-content font-bold">
                                {{ $customer->created_at->format('M d, Y') }}
                            </span>
                        </div>
                    </x-ui.table.td>

                    <x-ui.table.td align="right">
                        <div class="flex items-center justify-end gap-2">
                            <a href="{{ route('admin.customers.show', $customer) }}" wire:navigate class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-[#18542A]/10 text-[#18542A] text-[12px] font-bold hover:bg-[#18542A]/20 transition-colors">
                                @include('layouts.partials.icons.eye', ['class' => 'w-3.5 h-3.5'])
                                {{ __('View') }}
                            </a>
                            <a href="{{ route('admin.customers.edit', $customer) }}" wire:navigate class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-base-200 text-base-content/60 text-[12px] font-bold hover:bg-base-300 transition-all">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" /></svg>
                                {{ __('Edit') }}
                            </a>
                            @if (Auth::user()->role === \App\Enums\UserRole::SuperAdmin)
                                <div class="w-px h-4 bg-base-content/5 mx-1"></div>
                                @if ($customer->user)
                                    <button wire:click="startAction({{ $customer->id }}, 'toggleStatus')" class="w-8 h-8 flex items-center justify-center rounded-lg {{ $customer->user->is_active ? 'text-warning hover:bg-warning/10' : 'text-success hover:bg-success/10' }} transition-colors" title="{{ $customer->user->is_active ? __('Disable Account') : __('Enable Account') }}">
                                        @if ($customer->user->is_active)
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636" /></svg>
                                        @else
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                        @endif
                                    </button>
                                @endif
                                <button wire:click="startAction({{ $customer->id }}, 'delete')" class="w-8 h-8 flex items-center justify-center rounded-lg text-error hover:bg-error/10 transition-colors" title="{{ __('Delete Customer') }}">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201l.03-.614a2.25 2.25 0 012.244-2.077H15.84a2.25 2.25 0 012.244 2.077l.03.614z" /></svg>
                                </button>
                            @endif
                        </div>
                    </x-ui.table.td>
                </x-ui.table.row>
            @empty
                <x-ui.table.empty colspan="6" />
            @endforelse
        </tbody>

        <x-slot name="pagination">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div class="text-[12px] text-base-content/40 font-medium">
                    {{ __('Navigate through customers using the pagination links below.') }}
                </div>
                <div class="flex items-center justify-end gap-2">
                    {{ $customers->links(data: ['scrollTo' => false]) }}
                </div>
            </div>
        </x-slot>
    </x-ui.table>

    {{-- Security Confirmation Modal --}}
    <x-ui.modal wire:model="showConfirmationModal" title="{{ $sensitiveAction === 'delete' ? __('Confirm Deletion') : __('Confirm Account Change') }}" icon="heroicon-o-shield-check">
        <form wire:submit="executeSensitiveAction" class="space-y-6">
            <div class="p-4 bg-error/5 rounded-lg border border-error/10">
                <div class="flex gap-3">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-error shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
                    <div class="space-y-1">
                        <p class="text-[13px] font-bold text-error">
                            @if($sensitiveAction === 'delete')
                                {{ __('Critical Action: Irreversible Deletion') }}
                            @else
                                {{ __('Sensitive Action: Account Status Change') }}
                            @endif
                        </p>
                        <p class="text-[12px] text-error/80 font-medium">
                            @if($sensitiveAction === 'delete')
                                {{ __('You are about to permanently remove this customer and all associated data. This action cannot be undone.') }}
                            @else
                                {{ __('You are about to change the active status of this customer account. This will affect their ability to log in.') }}
                            @endif
                        </p>
                    </div>
                </div>
            </div>

            <div class="space-y-2">
                <label class="text-[11px] font-bold uppercase tracking-widest text-base-content/60">{{ __('Security Password Verification') }}</label>
                <x-ui.input 
                    wire:model="confirmationPassword" 
                    type="password" 
                    placeholder="{{ __('Enter your admin password to confirm') }}" 
                    required 
                />
                @error('confirmationPassword') <p class="text-[11px] text-error font-bold mt-1.5">{{ $message }}</p> @enderror
            </div>

            <div class="flex items-center justify-end gap-3 pt-4 border-t border-base-content/5 mt-6">
                <x-ui.button variant="ghost" type="button" wire:click="$set('showConfirmationModal', false)">
                    {{ __('Abort Action') }}
                </x-ui.button>
                <x-ui.button type="submit" variant="error" :loading="$loading === 'executeSensitiveAction'">
                    {{ $sensitiveAction === 'delete' ? __('Confirm & Delete') : __('Confirm & Update Status') }}
                </x-ui.button>
            </div>
        </form>
    </x-ui.modal>
</div>