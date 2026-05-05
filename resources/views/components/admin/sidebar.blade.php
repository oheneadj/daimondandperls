@props([])

@php
    $logoPath     = dpc_setting('business_logo');
    $logoUrl      = $logoPath ? \Illuminate\Support\Facades\Storage::disk('public')->url($logoPath) : null;
    $businessName = dpc_setting('business_name', 'Diamonds & Pearls');

    $navGroups = [
        'MAIN' => [
            ['label' => 'Dashboard', 'route' => 'admin.dashboard', 'icon' => 'squares-2x2'],
        ],
        'OPERATIONS' => [
            ['label' => 'Bookings', 'route' => 'admin.bookings.index', 'icon' => 'clipboard-document-list', 'badge' => auth()->user()->unreadBookingsCount() ?? 0, 'pattern' => 'admin.bookings.*', 'permission' => 'manage_bookings'],
            ['label' => 'Events', 'route' => 'admin.events.index', 'icon' => 'table-cells', 'pattern' => 'admin.events.*', 'permission' => 'manage_events'],
            ['label' => 'Packages', 'route' => 'admin.manage-packages.index', 'icon' => 'cake', 'pattern' => 'admin.manage-packages.*', 'permission' => 'manage_packages'],
            ['label' => 'Booking Windows', 'route' => 'admin.booking-windows.index', 'icon' => 'calendar', 'pattern' => 'admin.booking-windows.*', 'permission' => 'manage_packages'],
            ['label' => 'Collections', 'route' => 'admin.categories.index', 'icon' => 'tag', 'pattern' => 'admin.categories.*', 'permission' => 'manage_categories'],
            ['label' => 'Payments', 'route' => 'admin.payments.index', 'icon' => 'credit-card', 'badge' => auth()->user()->pendingPaymentsCount() ?? 0, 'pattern' => 'admin.payments.*', 'permission' => 'manage_payments'],
            ['label' => 'Customers', 'route' => 'admin.customers.index', 'icon' => 'user-group', 'pattern' => 'admin.customers.*', 'permission' => 'manage_customers'],
            ['label' => 'Contact Messages', 'route' => 'admin.contact-messages', 'icon' => 'bell', 'badge' => auth()->user()->newContactMessagesCount(), 'permission' => 'manage_contact_messages'],
        ],
        'STAFF' => [
            ['label' => 'Admins & Staff', 'route' => 'admin.users.index', 'icon' => 'user-group', 'pattern' => 'admin.users.*', 'permission' => 'manage_users'],
            ['label' => 'Roles & Perms', 'route' => 'admin.roles.index', 'icon' => 'shield-check', 'pattern' => 'admin.roles.*', 'permission' => 'manage_roles'],
        ],
        'SYSTEM' => [
            ['label' => 'Reviews', 'route' => 'admin.reviews.index', 'icon' => 'star', 'pattern' => 'admin.reviews.*', 'permission' => 'manage_bookings'],
            ['label' => 'Reports', 'route' => 'admin.reports.index', 'icon' => 'chart-bar-square', 'pattern' => 'admin.reports.*', 'permission' => 'manage_reports'],
            ['label' => 'System Logs', 'route' => 'admin.error-logs.index', 'icon' => 'exclamation-triangle-solid', 'pattern' => 'admin.error-logs.*', 'permission' => 'view_error_logs'],
            ['label' => 'Settings', 'route' => 'admin.settings.index', 'icon' => 'cog-6-tooth', 'pattern' => 'admin.settings.*', 'permission' => 'manage_settings'],
        ],
    ];

    $isSuperAdmin = auth()->user()->hasRole('super_admin');
@endphp

<aside
    {{ $attributes->merge(['class' => 'fixed inset-y-0 left-0 z-50 w-64 bg-neutral flex flex-col transition-transform duration-300 -translate-x-full lg:translate-x-0']) }}
    :class="mobileMenuOpen ? '!translate-x-0' : '-translate-x-full'"
>
    {{-- Logo Area --}}
    <div class="p-5 border-b border-white/[0.06] flex items-center justify-between">
        <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 min-w-0">
            {{-- Logo avatar --}}
            <div class="size-10 rounded-xl bg-primary/20 border border-primary/30 flex items-center justify-center shrink-0 overflow-hidden">
                @if($logoUrl)
                    <img src="{{ $logoUrl }}" alt="{{ $businessName }}" class="size-full object-cover">
                @else
                    <x-app-logo-icon class="size-5 fill-primary" />
                @endif
            </div>
            {{-- Business name --}}
            <div class="flex flex-col leading-none min-w-0">
                <span class="text-[15px] font-bold text-white tracking-tight truncate">{{ $businessName ?: 'DPCatering' }}</span>
                <span class="text-[10px] font-semibold uppercase tracking-[0.12em] text-white/35 mt-0.5">Catering Services</span>
            </div>
        </a>
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
                            $canSee = !isset($item['permission']) || $isSuperAdmin || auth()->user()->hasPermission($item['permission']);
                        @endphp
                        @if(!$canSee) @continue @endif
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
                    <span class="text-[11px] text-white/40 leading-none">{{ auth()->user()->roles->first()?->name ?? 'Administrator' }}</span>
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
        <p class="mt-3 text-center text-[11px] text-white/40 font-medium">Powered by <a href="https://diamondtechgh.com" target="_blank" rel="noopener" class="text-white/60 hover:text-white transition-colors font-semibold">Diamond Tech</a></p>
    </div>
</aside>
