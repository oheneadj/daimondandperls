<div class="bg-base-100 min-h-screen pb-24" x-data="{
        showDetails: false,
        selectedPackage: null,
        packageInCart: false,
        selectedWindowInfo: null,
        openDetails(pkg, inCart = false, windowInfo = null) {
            this.selectedPackage = pkg;
            this.packageInCart = inCart;
            this.selectedWindowInfo = windowInfo;
            this.showDetails = true;
        },
        showWindowPopup: false,
        windowDeliveryDate: '',
        windowIsNextWeek: false,
    }"
    @window-booking-info.window="showWindowPopup = true; windowDeliveryDate = $event.detail.date; windowIsNextWeek = $event.detail.isNextWeek">
    <!-- Header Section -->
    <header class="bg-primary relative overflow-hidden py-16 lg:py-24">
        {{-- Crosshatch texture --}}
        <div class="absolute inset-0 bg-[url('data:image/svg+xml,%3Csvg width=\'60\' height=\'60\' viewBox=\'0 0 60 60\' xmlns=\'http://www.w3.org/2000/svg\'%3E%3Cg fill=\'none\' fill-rule=\'evenodd\'%3E%3Cg fill=\'%23ffffff\' fill-opacity=\'0.03\'%3E%3Cpath d=\'M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z\'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E')]"
            aria-hidden="true"></div>

        {{-- Blobs --}}
        <div class="absolute top-0 right-0 size-[500px] bg-white/8 blur-[100px] rounded-full -translate-y-1/3 translate-x-1/4"
            aria-hidden="true"></div>
        <div class="absolute bottom-0 left-1/4 size-[350px] bg-black/15 blur-[80px] rounded-full translate-y-1/2"
            aria-hidden="true"></div>
        <div class="absolute top-1/2 left-0 size-[250px] bg-white/5 blur-[60px] rounded-full -translate-y-1/2 -translate-x-1/2"
            aria-hidden="true"></div>

        {{-- Floating food image cards (desktop only) --}}
        <div class="absolute right-0 top-0 bottom-0 w-[45%] hidden lg:flex items-center justify-end pr-8 xl:pr-16 gap-4"
            aria-hidden="true">
            {{-- Main large image --}}
            <div
                class="relative w-52 xl:w-64 aspect-[3/4] rounded-2xl overflow-hidden shadow-2xl shadow-black/30 rotate-2">
                <img src="{{ asset('images/dpc/large-party-size-jollof-rice-catering-tray.jpg.webp') }}"
                    alt="Large party-size jollof rice catering tray — Diamonds &amp; Pearls Catering Accra"
                    class="w-full h-full object-cover" loading="eager" fetchpriority="high">
                <div class="absolute inset-0 bg-gradient-to-t from-black/50 to-transparent"></div>
                <div class="absolute bottom-3 left-3 right-3">
                    <span
                        class="text-white text-[10px] font-bold uppercase tracking-widest bg-white/20 backdrop-blur-sm px-2.5 py-1 rounded-full">Ghanaian
                        Cuisine</span>
                </div>
            </div>
            {{-- Stacked smaller images --}}
            <div class="flex flex-col gap-3">
                <div class="w-36 xl:w-44 aspect-square rounded-2xl overflow-hidden shadow-xl shadow-black/20 -rotate-1">
                    <img src="{{ asset('images/dpc/jollof-rice-and-fried-chicken-takeaway-meal-1.jpg.webp') }}"
                        alt="Jollof rice and fried chicken — Diamonds &amp; Pearls Catering"
                        class="w-full h-full object-cover" loading="lazy" decoding="async">
                </div>
                <div class="w-36 xl:w-44 aspect-square rounded-2xl overflow-hidden shadow-xl shadow-black/20 rotate-1">
                    <img src="{{ asset('images/dpc/fried-yam-wedges-with-onions-and-peppers.jpg.webp') }}"
                        alt="Fried yam wedges with onions and peppers — Diamonds &amp; Pearls Catering"
                        class="w-full h-full object-cover" loading="lazy" decoding="async">
                </div>
            </div>
        </div>

        {{-- Decorative floating pills --}}

        <div class="container mx-auto px-4 lg:px-8 relative z-10 lg:max-w-[55%] xl:max-w-[50%]">
            <div
                class="inline-flex items-center gap-2 bg-white/15 text-white text-[11px] font-bold px-4 py-2 rounded-full uppercase tracking-widest mb-6">
                <span class="size-2 rounded-full bg-accent animate-pulse"></span>
                {{ __('Our Culinary Collections') }}
            </div>
            <h1 class="text-4xl sm:text-5xl lg:text-6xl font-semibold text-white leading-[1.1] tracking-tight">
                Exquisite catering for every <span class="text-accent">occasion</span>.
            </h1>
            <p class="mt-4 text-[15px] text-white/60 font-medium max-w-lg">
                From daily meals to large-scale events. Trusted by the UNDP and FDA certified to deliver excellence.
            </p>

            {{-- Quick stats row --}}
            {{-- <div class="mt-8 flex items-center gap-6">
                <div>
                    <div class="text-xl font-bold text-white">{{ $packages->count() }}+</div>
                    <div class="text-[10px] font-bold text-white/50 uppercase tracking-widest">Packages</div>
                </div>
                <div class="w-px h-8 bg-white/20"></div>
                <div>
                    <div class="text-xl font-bold text-white">{{ $categories->count() }}</div>
                    <div class="text-[10px] font-bold text-white/50 uppercase tracking-widest">Categories</div>
                </div>
                <div class="w-px h-8 bg-white/20"></div>
                <div>
                    <div class="text-xl font-bold text-white">GHS 500+</div>
                    <div class="text-[10px] font-bold text-white/50 uppercase tracking-widest">Starting from</div>
                </div>
            </div> --}}
        </div>
    </header>

    <!-- Filter & Search Strip -->
    <div class="sticky top-[68px] z-40 bg-base-100/95 backdrop-blur-md border-b border-base-content/8 py-4 shadow-sm">
        <div class="container mx-auto px-4 lg:px-8 flex flex-col sm:flex-row justify-between items-center gap-4">
            <div class="flex items-center gap-2 overflow-x-auto pb-1 sm:pb-0 w-full sm:w-auto"
                style="scrollbar-width: none;">
                <button wire:click="$set('categorySlug', '')" @class([
                    'shrink-0 inline-flex items-center gap-1.5 px-4 py-2 text-[12px] font-bold rounded-lg transition-all',
                    'bg-primary text-white shadow-sm' => $categorySlug === '',
                    'bg-base-200 text-base-content/60 hover:bg-base-300 hover:text-base-content' => $categorySlug !== '',
                ])>
                    @if($categorySlug === '')
                        <svg xmlns="http://www.w3.org/2000/svg" class="size-3.5" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                        </svg>
                    @endif
                    {{ __('All') }}
                </button>
                @foreach($categories as $category)
                    <button wire:click="$set('categorySlug', '{{ $category->slug }}')" @class([
                        'shrink-0 inline-flex items-center gap-1.5 px-4 py-2 text-[12px] font-bold rounded-lg transition-all whitespace-nowrap',
                        'bg-primary text-white shadow-sm' => $categorySlug === $category->slug,
                        'bg-base-200 text-base-content/60 hover:bg-base-300 hover:text-base-content' => $categorySlug !== $category->slug,
                    ])>
                        @if($categorySlug === $category->slug)
                            <svg xmlns="http://www.w3.org/2000/svg" class="size-3.5" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                            </svg>
                        @endif
                        {{ $category->name }}
                    </button>
                @endforeach
            </div>

            <div class="relative w-full sm:w-80">
                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                    <svg class="w-4 h-4 text-base-content/40" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>
                <input wire:model.live.debounce.300ms="search" type="text" placeholder="{{ __('Search packages...') }}"
                    class="w-full pl-10 pr-4 py-2.5 bg-base-200 border-transparent focus:border-primary focus:ring-2 focus:ring-primary/20 rounded-lg transition-all text-[13px] font-medium placeholder:text-base-content/30">
            </div>
        </div>
    </div>

    {{-- Booking Window Banner (shown when a package in filtered view has an active window) --}}
    @php
        $bannerWindow = $activeCategory
            ? collect($activeWindows)->first(fn($w) => $w !== null)
            : null;
        $bannerWs = $bannerWindow ? app(\App\Services\BookingWindowService::class)->getStatus($bannerWindow) : null;
    @endphp
    @if($bannerWs)
        <div class="border-b border-base-content/5">
            <div class="container mx-auto px-4 lg:px-8 py-3">
                @if($bannerWs['open'])
                    <div class="flex flex-wrap items-center gap-3 text-[12px]" x-data="{
                                            deadline: {{ $bannerWs['cutoff']->timestamp * 1000 }},
                                            label: '',
                                            tick() {
                                                const diff = this.deadline - Date.now();
                                                if (diff <= 0) { this.label = 'Booking window is now closed'; return; }
                                                const h = Math.floor(diff / 3600000);
                                                const m = Math.floor((diff % 3600000) / 60000);
                                                const s = Math.floor((diff % 60000) / 1000);
                                                this.label = h > 0 ? `${h}h ${m}m left` : `${m}m ${s}s left`;
                                            }
                                        }" x-init="tick(); setInterval(() => tick(), 1000)">
                        <span
                            class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-success/10 text-success font-bold">
                            <span class="w-1.5 h-1.5 rounded-full bg-success animate-pulse"></span>
                            Delivering {{ $bannerWs['deliveryDayLabel'] }}
                        </span>
                        <span class="text-base-content/50">Book before <strong
                                class="text-base-content">{{ $bannerWs['cutoffLabel'] }},
                                {{ substr($bannerWs['cutoff']->format('H:i'), 0, 5) }}</strong> to make this delivery</span>
                        <span class="text-warning font-bold" x-text="label"></span>
                    </div>
                @else
                    <div class="flex flex-wrap items-center gap-3 text-[12px]">
                        <span
                            class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-warning/10 text-warning font-bold">
                            <span class="w-1.5 h-1.5 rounded-full bg-warning"></span>
                            Cutoff passed
                        </span>
                        <span class="text-base-content/50">Orders placed now will be delivered on <strong
                                class="text-base-content">{{ $bannerWs['scheduledDelivery']->format('D, M j') }}</strong></span>
                    </div>
                @endif
            </div>
        </div>
    @endif

    <!-- Main Content Grid -->
    <div class="container mx-auto px-4 lg:px-8 py-12 lg:py-20">
        @if($packages->isEmpty())
            <div
                class="text-center py-32 bg-base-100 rounded-[40px] border-2 border-base-content/5 border-dashed flex flex-col items-center">
                <div class="w-24 h-24 bg-base-200 rounded-full flex items-center justify-center mb-8 shadow-inner">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-12 h-12 text-base-content/20" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>
                <h3 class="text-3xl font-bold text-base-content">{{ __('No packages found') }}</h3>
                <p class="text-base-content/50 mt-4 font-medium text-[16px] max-w-sm leading-relaxed">
                    {{ __('We couldn\'t find any packages matching those criteria. Try widening your search or clearing filters.') }}
                </p>
                <button wire:click="$set('categorySlug', ''); $set('search', '')"
                    class="mt-10 px-8 py-3 bg-primary text-white font-bold rounded-2xl shadow-lg shadow-primary/20 hover:scale-105 transition-transform">
                    {{ __('Clear all filters') }}
                </button>
            </div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-5 md:gap-8">
                @foreach($packages as $package)
                    @php
                        $inCart = $cartItems->has($package->id);
                        $aw = $activeWindows[$package->id] ?? null;
                        $wi = null;
                        if ($aw) {
                            $aws = app(\App\Services\BookingWindowService::class)->getStatus($aw);
                            $wi = [
                                'open' => $aws['open'],
                                'cutoffTs' => $aws['cutoff']->timestamp * 1000,
                                'cutoffLabel' => $aws['cutoffLabel'],
                                'cutoffTime' => substr($aws['cutoff']->format('H:i'), 0, 5),
                                'deliveryLabel' => $aws['deliveryDayLabel'],
                                'deliveryDate' => $aws['scheduledDelivery']->format('D, M j'),
                            ];
                        }
                    @endphp
                    <div x-data="{ pkg: @js($package), wi: @js($wi) }"
                        @click="openDetails(pkg, {{ $inCart ? 'true' : 'false' }}, wi)">
                        <x-package-card :package="$package" :selected="$inCart" :activeWindow="$aw" />
                    </div>
                @endforeach
            </div>
        @endif
    </div>

    <!-- Booking Bar (Dynamic) — desktop only -->
    @if($cartCount > 0)
        <div class="hidden sm:block fixed left-1/2 -translate-x-1/2 z-50 w-[95%] max-w-xl animate-in fade-in slide-in-from-bottom-5 border border-accent rounded-3xl duration-500"
            style="bottom: max(2rem, env(safe-area-inset-bottom, 2rem))">
            <div class="booking-bar-status">
                <div class="booking-count-badge">
                    {{ $cartCount }}
                </div>
                <div class="flex-1 min-w-0">
                    <div class="text-[10px] font-bold uppercase tracking-widest text-white/40 mb-0.5">
                        {{ __('Added to booking') }}
                    </div>
                    <div class="text-[14px] font-bold truncate text-white">
                        @if($cartCount === 1)
                            {{ $cartItems->first()['package']->name }}
                        @else
                            {{ $cartCount }} packages selected
                        @endif
                    </div>
                </div>
                <button onclick="window.location.href='{{ route('checkout') }}'"
                    class="px-5 py-2.5 bg-white text-[#121212] font-black text-[12px] uppercase tracking-wider rounded-xl hover:bg-primary hover:text-white transition-all shadow-md active:scale-95">
                    {{ __('Proceed to book') }}
                </button>
            </div>
        </div>
    @endif

    <!-- Footer View All Button (Mockup) -->
    <div class="container mx-auto px-4 lg:px-8 mt-12 mb-20">
        <button
            class="w-full py-5 bg-base-200 hover:bg-base-300 rounded-3xl text-[14px] font-bold text-base-content/60 border border-base-content/5 transition-all text-center">
            {{ __('View full catering menu (PDF version available)') }} →
        </button>
    </div>

    <!-- Details Modal (Alpine.js) -->
    <!-- Package Details Modal Component -->
    <x-package-details-modal />

    {{-- Post-cutoff window popup --}}
    <div x-show="showWindowPopup" x-transition
        class="fixed inset-0 z-[200] flex items-end sm:items-center justify-center p-4" style="display: none;">
        <div class="absolute inset-0 bg-black/40 backdrop-blur-sm" @click="showWindowPopup = false"></div>
        <div class="relative bg-base-100 rounded-2xl shadow-2xl p-6 max-w-sm w-full z-10">
            <div class="flex items-start gap-4">
                <div class="w-12 h-12 rounded-xl bg-primary/10 flex items-center justify-center shrink-0">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-primary" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                </div>
                <div class="flex-1">
                    <h3 class="font-bold text-base-content text-[15px]"
                        x-text="windowIsNextWeek ? 'Added — next week\'s delivery' : 'Added — delivery confirmed'"></h3>
                    <p class="text-[13px] text-base-content/60 mt-1">
                        <template x-if="windowIsNextWeek">
                            <span>The cutoff for this week has passed. Your order will be delivered on <strong
                                    class="text-base-content" x-text="windowDeliveryDate"></strong>.</span>
                        </template>
                        <template x-if="!windowIsNextWeek">
                            <span>Your order will be delivered on <strong class="text-base-content"
                                    x-text="windowDeliveryDate"></strong>.</span>
                        </template>
                    </p>
                </div>
            </div>
            <div class="mt-5 flex gap-3">
                <button @click="showWindowPopup = false"
                    class="flex-1 py-2.5 bg-base-200 rounded-xl text-[13px] font-bold text-base-content/70 hover:bg-base-300 transition-colors">Got
                    it</button>
                <a href="{{ route('checkout') }}"
                    class="flex-1 py-2.5 bg-primary rounded-xl text-[13px] font-bold text-white text-center hover:bg-primary/90 transition-colors">Go
                    to checkout</a>
            </div>
        </div>
    </div>
</div>