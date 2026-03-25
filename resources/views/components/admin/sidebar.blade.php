@props([])

<aside 
    {{ $attributes->merge(['class' => 'fixed inset-y-0 left-0 z-50 w-64 bg-[#1C1A18] flex flex-col transition-transform duration-300 lg:translate-x-0']) }}
    :class="mobileMenuOpen ? 'translate-x-0' : '-translate-x-full'"
>
    <!-- Sidebar Header (Logo) -->
    <div class="p-5 border-b border-white/10 flex items-center justify-between h-16">
        <div class="flex flex-col">
            <span class=" text-[20px] font-semibold text-white leading-tight">Diamonds & Pearls</span>
            <span class="text-[11px] font-bold uppercase tracking-[0.2em] text-white/40 mt-1 italic">Catering Services</span>
        </div>
        <button @click="mobileMenuOpen = false" class="p-2 lg:hidden text-white/50 hover:text-white transition-colors">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>
    </div>

    <!-- Navigation Links -->
    <nav class="flex-1 px-3 py-5 overflow-y-auto space-y-8">
        <!-- Platform Section -->
        <div>
            <span class="px-3 text-[11px] font-bold uppercase tracking-[0.2em] text-white/30">{{ __('Platform') }}</span>
            <ul class="mt-3 space-y-1">
                <li>
                    <x-admin.sidebar-item 
                        label="Dashboard" 
                        icon="squares-2x2" 
                        :href="route('admin.dashboard')" 
                        :active="request()->routeIs('admin.dashboard')" 
                    />
                </li>
            </ul>
        </div>

        <!-- Operations Section -->
        <div>
            <span class="px-3 text-[11px] font-bold uppercase tracking-[0.2em] text-white/30">{{ __('Operations') }}</span>
            <ul class="mt-3 space-y-1">
                <li>
                    <x-admin.sidebar-item 
                        label="Bookings" 
                        icon="calendar-days" 
                        :href="route('admin.bookings.index')" 
                        :active="request()->routeIs('admin.bookings.*')" 
                        :badge="auth()->user()->unreadBookingsCount() > 0 ? auth()->user()->unreadBookingsCount() : null"
                    />
                </li>
                <li>
                    <x-admin.sidebar-item 
                        label="Packages" 
                        icon="archive-box" 
                        :href="route('admin.manage-packages.index')" 
                        :active="request()->routeIs('admin.manage-packages.*')" 
                    />
                </li>
                <li>
                    <x-admin.sidebar-item 
                        label="Payments" 
                        icon="banknotes" 
                        :href="route('admin.payments.index')" 
                        :active="request()->routeIs('admin.payments.*')" 
                        :badge="auth()->user()->pendingPaymentsCount() > 0 ? auth()->user()->pendingPaymentsCount() : null"
                    />
                </li>
                <li>
                    <x-admin.sidebar-item 
                        label="Reports" 
                        icon="chart-bar" 
                        :href="route('admin.reports.index')" 
                        :active="request()->routeIs('admin.reports.*')" 
                    />
                </li>
            </ul>
        </div>

        <!-- Configuration Section -->
        <div>
            <span class="px-3 text-[11px] font-bold uppercase tracking-[0.2em] text-white/30">{{ __('Management') }}</span>
            <ul class="mt-3 space-y-1">
                <li>
                    <x-admin.sidebar-item 
                        label="Categories" 
                        icon="tag" 
                        :href="route('admin.categories.index')" 
                        :active="request()->routeIs('admin.categories.*')" 
                    />
                </li>
                <li>
                    <x-admin.sidebar-item 
                        label="Customers" 
                        icon="user-group" 
                        :href="route('admin.customers.index')" 
                        :active="request()->routeIs('admin.customers.*')" 
                    />
                </li>
                <li>
                    <x-admin.sidebar-item 
                        label="Settings" 
                        icon="cog-6-tooth" 
                        :href="route('admin.settings.index')" 
                        :active="request()->routeIs('admin.settings.*')" 
                    />
                </li>
            </ul>
        </div>
    </nav>

    <!-- Sidebar Footer -->
    <div class="px-6 py-5 border-t border-white/5">
        <div class="flex items-center gap-3">
            <div class="w-1.5 h-1.5 rounded-full bg-primary animate-pulse"></div>
            <span class="text-[10px] font-bold uppercase tracking-widest text-white/40">{{ __('Operational Suite v1.5') }}</span>
        </div>
    </div>
</aside>
