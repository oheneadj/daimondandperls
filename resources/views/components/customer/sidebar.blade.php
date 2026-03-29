@props([])

<aside
    {{ $attributes->merge(['class' => 'fixed inset-y-0 left-0 z-50 w-64 bg-white border-r border-base-content/10 flex flex-col transition-transform duration-300 -translate-x-full lg:translate-x-0']) }}
    :class="mobileMenuOpen ? '!translate-x-0' : '-translate-x-full'"
>
    <!-- Sidebar Header (Logo) -->
    <div class="p-5 border-b border-base-content/10 flex items-center justify-between h-16">
        <a href="{{ route('home') }}" class="flex flex-col">
            <span class="text-[20px] font-semibold text-base-content leading-tight">Diamonds & Pearls</span>
            <span class="text-[11px] font-bold uppercase tracking-[0.2em] text-primary/60 mt-1 italic">Catering Services</span>
        </a>
        <button @click="mobileMenuOpen = false" class="p-2 lg:hidden text-base-content/50 hover:text-base-content transition-colors">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>
    </div>

    <!-- Navigation Links -->
    <nav class="flex-1 px-3 py-6 overflow-y-auto space-y-1">
        <x-customer.sidebar-item
            label="Dashboard"
            icon="squares-2x2"
            :href="route('dashboard.index')"
            :active="request()->routeIs('dashboard.index')"
        />
        <x-customer.sidebar-item
            label="My Bookings"
            icon="calendar-days"
            :href="route('dashboard.bookings.index')"
            :active="request()->routeIs('dashboard.bookings.*')"
        />
        <x-customer.sidebar-item
            label="Payments"
            icon="banknotes"
            :href="route('dashboard.payments.index')"
            :active="request()->routeIs('dashboard.payments.*')"
        />
        <x-customer.sidebar-item
            label="Profile"
            icon="user-circle"
            :href="route('dashboard.profile')"
            :active="request()->routeIs('dashboard.profile')"
        />
    </nav>

    <!-- Sidebar Footer -->
    <div class="px-6 py-5 border-t border-base-content/10">
        <div class="flex items-center gap-3">
            <div class="w-1.5 h-1.5 rounded-full bg-primary animate-pulse"></div>
            <span class="text-[10px] font-bold uppercase tracking-widest text-base-content/40">{{ __('Customer Portal') }}</span>
        </div>
    </div>
</aside>
