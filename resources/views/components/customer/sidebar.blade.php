@props([])

@php
    $logoPath    = dpc_setting('business_logo');
    $logoUrl     = $logoPath ? \Illuminate\Support\Facades\Storage::disk('public')->url($logoPath) : null;
    $businessName = dpc_setting('business_name', 'Diamonds & Pearls');

    $navGroups = [
        'MAIN' => [
            ['label' => 'Dashboard', 'route' => 'dashboard.index', 'icon' => 'squares-2x2'],
        ],
        'BOOKINGS' => [
            ['label' => 'Meal Orders', 'route' => 'dashboard.meals.index', 'icon' => 'cake', 'pattern' => 'dashboard.meals.*'],
            ['label' => 'Events', 'route' => 'dashboard.events.index', 'icon' => 'clipboard-document-list', 'pattern' => 'dashboard.events.*'],
            ['label' => 'Payments', 'route' => 'dashboard.payments.index', 'icon' => 'banknotes', 'pattern' => 'dashboard.payments.*'],
        ],
        'ACCOUNT' => [
            ['label' => 'Loyalty & Points', 'route' => 'dashboard.loyalty', 'icon' => 'star'],
            ['label' => 'Payment Methods', 'route' => 'dashboard.payment-methods', 'icon' => 'credit-card'],
            ['label' => 'Profile', 'route' => 'dashboard.profile', 'icon' => 'user-circle'],
        ],
    ];
@endphp

<aside
    {{ $attributes->merge(['class' => 'fixed inset-y-0 left-0 z-50 w-64 bg-neutral flex flex-col transition-transform duration-300 -translate-x-full lg:translate-x-0']) }}
    :class="mobileMenuOpen ? '!translate-x-0' : '-translate-x-full'"
>
    {{-- Logo Area --}}
    <div class="p-5 border-b border-white/[0.06] flex items-center justify-between">
        <a href="{{ route('home') }}" class="flex items-center gap-3 min-w-0">
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

    {{-- Customer User Block --}}
    <div class="mt-auto p-4 border-t border-white/10">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 rounded-full bg-primary flex items-center justify-center text-base-content text-[12px] font-bold border border-white/10 shadow-sm">
                    {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
                </div>
                <div class="flex flex-col overflow-hidden">
                    <span class="text-[13px] font-semibold text-white truncate max-w-[120px]">{{ auth()->user()->name }}</span>
                    <span class="text-[11px] text-white/40 leading-none">Customer</span>
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
