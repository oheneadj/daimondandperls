@props([])

@php
    $navGroups = [
        'MAIN' => [
            ['label' => 'Dashboard', 'route' => 'admin.dashboard', 'icon' => 'squares-2x2'],
        ],
        'OPERATIONS' => [
            ['label' => 'Bookings', 'route' => 'admin.bookings.index', 'icon' => 'clipboard-document-list', 'badge' => auth()->user()->unreadBookingsCount() ?? 0, 'pattern' => 'admin.bookings.*'],
            ['label' => 'Events', 'route' => 'admin.events.index', 'icon' => 'table-cells', 'pattern' => 'admin.events.*'],
            ['label' => 'Packages', 'route' => 'admin.manage-packages.index', 'icon' => 'cake', 'pattern' => 'admin.manage-packages.*'],
            ['label' => 'Collections', 'route' => 'admin.categories.index', 'icon' => 'tag', 'pattern' => 'admin.categories.*'],
            ['label' => 'Payments', 'route' => 'admin.payments.index', 'icon' => 'credit-card', 'badge' => auth()->user()->pendingPaymentsCount() ?? 0, 'pattern' => 'admin.payments.*'],
            ['label' => 'Customers', 'route' => 'admin.customers.index', 'icon' => 'user-group', 'pattern' => 'admin.customers.*'],
        ],
        'STAFF' => [
            ['label' => 'Admins & Staff', 'route' => 'admin.users.index', 'icon' => 'user-group', 'pattern' => 'admin.users.*'],
            ['label' => 'Roles & Perms', 'route' => 'admin.roles.index', 'icon' => 'shield-check', 'pattern' => 'admin.roles.*'],
        ],
        'SYSTEM' => [
            ['label' => 'Reports', 'route' => 'admin.reports.index', 'icon' => 'chart-bar-square', 'pattern' => 'admin.reports.*'],
            ['label' => 'Settings', 'route' => 'admin.settings.index', 'icon' => 'cog-6-tooth', 'pattern' => 'admin.settings.*'],
        ],
    ];
@endphp

<aside
    {{ $attributes->merge(['class' => 'fixed inset-y-0 left-0 z-50 w-64 bg-neutral flex flex-col transition-transform duration-300 -translate-x-full lg:translate-x-0']) }}
    :class="mobileMenuOpen ? '!translate-x-0' : '-translate-x-full'"
>
    {{-- Logo Area --}}
    <div class="p-6 border-b border-white/[0.03] flex items-center justify-between">
        <div class="flex flex-col">
            <span class="text-[18px] font-bold text-[#f3e8cc] tracking-tight leading-tight">Diamonds & Pearls</span>
            <span class="text-[11px] font-bold uppercase tracking-widest text-[#9ABC05] mt-1.5 italic opacity-80">Catering Services</span>
        </div>
        <button @click="mobileMenuOpen = false" class="p-2 lg:hidden text-white/50 hover:text-white transition-colors">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>
    </div>

    {{-- Navigation --}}
    <nav class="flex-1 px-4 py-6 overflow-y-auto space-y-8">
        @foreach($navGroups as $group => $items)
            <div class="space-y-3">
                <span class="px-3 text-[10px] font-bold uppercase tracking-widest text-white/30">{{ __($group) }}</span>
                <ul class="space-y-1">
                    @foreach($items as $item)
                        @php
                            $isActive = request()->routeIs($item['route']) || (isset($item['pattern']) && request()->routeIs($item['pattern']));
                        @endphp
                        <li class="relative">
                            <a href="{{ route($item['route']) }}"
                               wire:navigate
                               @click="mobileMenuOpen = false"
                               class="flex items-center justify-between py-3 px-4 transition-all duration-200 group relative {{ $isActive ? 'bg-[#FFC926]/10 text-[#FFC926] border-l-[3px] border-[#FFC926]' : 'text-[#f3e8cc]/70 hover:bg-[#1a1a1a] hover:text-accent' }}">
                                <div class="flex items-center gap-3">
                                    @include('layouts.partials.icons.' . $item['icon'], ['class' => 'w-5 h-5 ' . ($isActive ? 'text-[#FFC926]' : 'text-[#f3e8cc]/40 group-hover:text-accent')])
                                    <span class="text-[13px] font-medium leading-none">{{ __($item['label']) }}</span>
                                </div>

                                @if(!empty($item['badge']) && $item['badge'] > 0)
                                    <span class="px-2 py-0.5 rounded-full {{ $isActive ? 'bg-[#FFC926]/20 text-[#FFC926]' : 'bg-white/5 text-white/40' }} text-[10px] font-bold leading-none min-w-[20px] text-center">
                                        {{ $item['badge'] }}
                                    </span>
                                @endif
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>
        @endforeach
    </nav>

    {{-- Admin User Block --}}
    <div class="mt-auto p-4 border-t border-white/10">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 rounded-full bg-primary flex items-center justify-center text-base-content text-[12px] font-bold border border-white/10 shadow-sm">
                    {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
                </div>
                <div class="flex flex-col overflow-hidden">
                    <span class="text-[13px] font-semibold text-white truncate max-w-[120px]">{{ auth()->user()->name }}</span>
                    <span class="text-[11px] text-white/40 leading-none">{{ ucfirst(auth()->user()->role?->value ?? 'Administrator') }}</span>
                </div>
            </div>

            <form method="POST" action="{{ route('logout') }}" x-data>
                @csrf
                <button type="submit" @click.prevent="$root.submit();" class="p-2 text-white/30 hover:text-primary transition-colors" title="Logout">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15m3 0l3-3m0 0l-3-3m3 3H9" />
                    </svg>
                </button>
            </form>
        </div>
    </div>
</aside>
