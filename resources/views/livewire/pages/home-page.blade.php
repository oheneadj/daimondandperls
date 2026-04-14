<div
    x-data="{
        showDetails: false,
        selectedPackage: null,
        packageInCart: false,
        selectedWindowInfo: null,
        openDetails(pkg, inCart = false, windowInfo = null) {
            this.selectedPackage = pkg;
            this.packageInCart = inCart;
            this.selectedWindowInfo = windowInfo;
            this.showDetails = true;
        }
    }"
>
    @php
        $whatsappNumber = \App\Models\Setting::where('key', 'business_whatsapp')->value('value') ?: '233244203181';
    @endphp

    {{-- 1. Hero Section --}}
    <section class="relative bg-base-100 min-h-[85vh] flex flex-col justify-center overflow-hidden">
        {{-- Subtle dot pattern --}}
        <div class="absolute inset-0 bg-[radial-gradient(circle,_#D5251820_1px,_transparent_1px)] bg-[size:28px_28px]" aria-hidden="true"></div>
        {{-- Red accent blob top-right --}}
        <div class="absolute top-0 right-0 size-[500px] bg-primary/8 blur-[100px] rounded-full -translate-y-1/3 translate-x-1/4" aria-hidden="true"></div>
        {{-- Green accent blob bottom-left --}}
        <div class="absolute bottom-0 left-0 size-[400px] bg-[#18542A]/8 blur-[100px] rounded-full translate-y-1/3 -translate-x-1/4" aria-hidden="true"></div>

        <div class="container mx-auto px-4 lg:px-8 relative z-10 pt-20 pb-16 lg:pt-28 lg:pb-24 grid grid-cols-1 lg:grid-cols-12 gap-12 lg:gap-8 items-center">
            <div class="lg:col-span-7">
                <div class="inline-flex items-center gap-2 bg-primary/10 text-primary text-[11px] font-bold px-4 py-2 rounded-full uppercase tracking-widest mb-6">
                    <span class="size-2 rounded-full bg-primary animate-pulse"></span>
                    Accra's Finest Catering
                </div>

                <h1 class="text-3xl sm:text-5xl lg:text-6xl font-semibold text-base-content leading-[1.1] mb-6 tracking-tight">
                    Premium food for<br class="hidden sm:block"> every <span class="text-primary">occasion</span>
                </h1>

                <p class="text-[15px] sm:text-lg text-base-content/60 leading-relaxed mb-8 max-w-xl font-medium">
                    Weddings · Corporate Events · Funerals · Naming Ceremonies.<br class="hidden sm:block">
                    Experience authentic Ghanaian flavours and impeccable service.
                </p>

                <div class="flex flex-col sm:flex-row gap-3 sm:gap-4 mb-8">
                    <x-ui.button href="{{ route('packages.browse') }}" variant="primary" size="lg" class="w-full sm:w-auto">
                        Order Simple Meal
                    </x-ui.button>
                    <x-ui.button href="{{ route('event-booking') }}" variant="green" size="lg" class="w-full sm:w-auto">
                        Plan an Event
                    </x-ui.button>
                </div>

                <div class="flex items-center gap-3 mb-10 flex-wrap">
                    <span class="text-[10px] font-bold text-base-content/40 uppercase tracking-widest pl-1">Trusted Payments:</span>
                    <div class="flex items-center gap-2.5">
                        <div class="bg-base-200 p-1.5 rounded-lg hover:-translate-y-0.5 transition-transform">
                            <img src="{{ asset('logos/mtn-momo.png') }}" alt="MTN MoMo" class="h-6 w-auto object-contain">
                        </div>
                        <div class="bg-base-200 p-1.5 rounded-lg hover:-translate-y-0.5 transition-transform">
                            <img src="{{ asset('logos/Telecel-Cash.jpg') }}" alt="Telecel Cash" class="h-6 w-auto object-contain rounded-[4px]">
                        </div>
                        <div class="bg-base-200 p-1.5 rounded-lg hover:-translate-y-0.5 transition-transform">
                            <img src="{{ asset('logos/airteltigo-money.png') }}" alt="AirtelTigo Money" class="h-6 w-auto object-contain">
                        </div>
                    </div>
                </div>

                <div class="flex items-center gap-6 sm:gap-8 md:gap-12 pt-6 border-t border-base-content/8">
                    <div>
                        <div class="text-2xl sm:text-3xl font-bold text-primary mb-1 tracking-tight">500+</div>
                        <div class="text-[9px] sm:text-[10px] font-bold text-base-content/40 uppercase tracking-widest">Events Served</div>
                    </div>
                    <div class="w-px h-8 sm:h-10 bg-base-content/10"></div>
                    <div>
                        <div class="text-2xl sm:text-3xl font-bold text-[#18542A] mb-1 tracking-tight">6+</div>
                        <div class="text-[9px] sm:text-[10px] font-bold text-base-content/40 uppercase tracking-widest">Years Exp.</div>
                    </div>
                    <div class="w-px h-8 sm:h-10 bg-base-content/10"></div>
                    <div>
                        <div class="flex gap-1 mb-1">
                            <svg class="size-6 sm:size-8 text-accent" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                        </div>
                        <div class="text-[9px] sm:text-[10px] font-bold text-base-content/40 uppercase tracking-widest">FDA Approved</div>
                    </div>
                </div>
            </div>

            {{-- Hero Image --}}
            <div class="lg:col-span-5 relative hidden lg:block pl-6">
                <div class="relative w-full aspect-[4/5] rounded-3xl bg-base-200 overflow-hidden shadow-2xl rotate-2 hover:rotate-0 transition-transform duration-500">
                    <img src="https://images.unsplash.com/photo-1547592180-85f173990554?q=80&w=1470" alt="Beautiful catering setup" class="w-full h-full object-cover">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent flex items-end p-8">
                        <div>
                            <div class="inline-flex items-center gap-1.5 bg-white/20 backdrop-blur-md text-white text-[11px] font-bold px-3 py-1.5 rounded-lg uppercase tracking-widest mb-3">
                                Signature Setup
                            </div>
                            <h3 class="text-2xl font-bold text-white leading-tight">Authentic Ghanaian Flavours</h3>
                        </div>
                    </div>
                </div>

                {{-- Floating badge --}}
                <div class="absolute -bottom-6 -left-6 bg-base-100 p-4 rounded-2xl shadow-xl flex items-center gap-4 z-20 hover:scale-105 transition-transform cursor-pointer">
                    <div class="size-12 bg-primary/10 text-primary rounded-xl flex items-center justify-center shrink-0">
                        <svg xmlns="http://www.w3.org/2000/svg" class="size-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    </div>
                    <div>
                        <p class="text-[13px] font-bold text-base-content">Top Rated in Accra</p>
                        <div class="flex items-center gap-0.5 text-accent mt-1">
                            @for($i=0; $i<5; $i++)
                            <svg class="size-4" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                            @endfor
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- 2. Promo Banner --}}
    <div class="bg-[#18542A] text-white py-3 px-4 relative shadow-sm" x-data="{ show: true }" x-show="show" x-transition.origin.top>
        <div class="container mx-auto flex items-center justify-center gap-3 md:gap-6 text-[13px] sm:text-[14px]">
            <span class="font-bold flex items-center gap-2 shrink-0">
                <svg xmlns="http://www.w3.org/2000/svg" class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                <span class="hidden sm:inline">Special Offer</span>
            </span>
            <span class="font-medium opacity-95 truncate">Book your next event and get a complimentary juice bar on us.</span>
            <a href="{{ route('packages.browse') }}" class="font-bold underline whitespace-nowrap hover:text-white/80 transition-colors">Claim Offer</a>
            <button @click="show = false" class="absolute right-4 top-1/2 -translate-y-1/2 p-1.5 hover:bg-white/10 rounded-lg transition-colors" title="Dismiss">
                <svg xmlns="http://www.w3.org/2000/svg" class="size-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
            </button>
        </div>
    </div>

    {{-- 3. How It Works --}}
    <x-home.how-it-works />

    {{-- 4. Packages & Pricing (Livewire-powered filtering) --}}
    <section id="packages" class="py-16 sm:py-24 bg-base-100">
        <div class="container mx-auto px-4 lg:px-8">
            <div class="flex flex-col md:flex-row justify-between items-start md:items-end mb-8 sm:mb-12 gap-4 sm:gap-8 pb-8 sm:pb-12">
                <div class="flex flex-col gap-2 relative">
                    <span class="text-[11px] font-bold text-primary uppercase tracking-[0.2em] mb-1 block">Our Offerings</span>
                    <h2 class="text-3xl sm:text-4xl lg:text-5xl font-semibold text-base-content tracking-tight">Packages & Pricing</h2>
                </div>

                <x-ui.button href="{{ route('packages.browse') }}" variant="black" size="md">
                    View All Packages
                    <svg xmlns="http://www.w3.org/2000/svg" class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" /></svg>
                </x-ui.button>
            </div>

            {{-- Category Filter Tabs --}}
            <div class="flex items-center gap-2 overflow-x-auto pb-4 mb-8" style="scrollbar-width: none;">
                <button
                    wire:click="selectCategory(null)"
                    @class([
                        'shrink-0 inline-flex items-center gap-1.5 text-[12px] font-bold px-4 py-2 rounded-lg transition-all',
                        'bg-primary text-white shadow-sm' => is_null($selectedCategory),
                        'bg-base-200 text-base-content/60 hover:bg-base-300 hover:text-base-content' => !is_null($selectedCategory),
                    ])
                >
                    @if(is_null($selectedCategory))
                        <svg xmlns="http://www.w3.org/2000/svg" class="size-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" /></svg>
                    @endif
                    All Packages
                </button>
                @foreach($categories as $category)
                    <button
                        wire:click="selectCategory({{ $category->id }})"
                        @class([
                            'shrink-0 inline-flex items-center gap-1.5 text-[12px] font-bold px-4 py-2 rounded-lg transition-all whitespace-nowrap',
                            'bg-primary text-white shadow-sm' => $selectedCategory === $category->id,
                            'bg-base-200 text-base-content/60 hover:bg-base-300 hover:text-base-content' => $selectedCategory !== $category->id,
                        ])
                    >
                        @if($selectedCategory === $category->id)
                            <svg xmlns="http://www.w3.org/2000/svg" class="size-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" /></svg>
                        @endif
                        {{ $category->name }}
                    </button>
                @endforeach
            </div>

            {{-- Package Cards Grid --}}
            <div wire:loading.class="opacity-50 transition-opacity" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5 sm:gap-8 items-stretch">
                @forelse($packages as $package)
                    @php $inCart = $cartItems->has($package->id); @endphp
                    @php
                        $ws = $windowStatuses[$package->category_id] ?? null;
                        $wi = null;
                        if ($ws && $ws['enabled'] && !$package->window_exempt) {
                            $wi = [
                                'open'          => $ws['open'],
                                'cutoffTs'      => $ws['cutoff']->timestamp * 1000,
                                'cutoffLabel'   => $ws['cutoffLabel'],
                                'cutoffTime'    => substr($ws['cutoff']->format('H:i'), 0, 5),
                                'deliveryLabel' => $ws['deliveryDayLabel'],
                                'deliveryDate'  => $ws['scheduledDelivery']->format('D, M j'),
                            ];
                        }
                    @endphp
                    <div wire:key="home-pkg-{{ $package->id }}" x-data="{ pkg: @js($package), wi: @js($wi) }" @click="openDetails(pkg, {{ $inCart ? 'true' : 'false' }}, wi)">
                        <x-package-card
                            :package="$package"
                            :selected="$inCart"
                            :windowStatus="$ws"
                        />
                    </div>
                @empty
                    <div class="col-span-full text-center py-16">
                        <p class="text-base-content/40 text-[15px] font-medium">No packages available in this category yet.</p>
                    </div>
                @endforelse
            </div>

            {{-- Custom Menu CTA --}}
            <div class="mt-16 bg-primary rounded-2xl p-6 md:p-10 flex flex-col md:flex-row items-center justify-between gap-6 text-center md:text-left">
                <div>
                    <h4 class="text-xl font-bold text-white mb-2">Need a custom menu?</h4>
                    <p class="text-[14px] text-white/60 font-medium">Don't see what you need? We can create a bespoke package tailored for your exact event size and budget.</p>
                </div>
                <a href="https://wa.me/{{ $whatsappNumber }}" target="_blank" class="bg-accent text-neutral text-[14px] font-bold px-8 py-3.5 rounded-xl hover:brightness-110 transition-all shadow-sm shrink-0 whitespace-nowrap">
                    Request Custom Quote
                </a>
            </div>
        </div>
    </section>

    {{-- 5. Event Types --}}
    <section class="py-16 sm:py-24 bg-base-200">
        <div class="container mx-auto px-4 lg:px-8">
            <div class="text-center mb-10 sm:mb-14">
                <span class="text-[11px] font-bold text-primary uppercase tracking-[0.2em] mb-3 block">Occasions</span>
                <h2 class="text-3xl sm:text-4xl lg:text-5xl font-semibold text-base-content tracking-tight">Events we cater for</h2>
            </div>

            @php
                $events = [
                    ['name' => 'Weddings',        'desc' => 'Traditional & White',       'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />'],
                    ['name' => 'Corporate Events','desc' => 'Retreats & Launches',        'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />'],
                    ['name' => 'Funerals',        'desc' => 'Family & Large Scale',      'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />'],
                    ['name' => 'Birthdays',       'desc' => 'Milestones & Parties',      'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 15.546c-.523 0-1.046.151-1.5.454a2.704 2.704 0 01-3 0 2.704 2.704 0 00-3 0 2.704 2.704 0 01-3 0 2.704 2.704 0 00-3 0 2.704 2.704 0 01-3 0A1.75 1.75 0 003 15.546V12a9 9 0 0118 0v3.546zM12 3v2" />'],
                    ['name' => 'Outdoorings',     'desc' => 'Naming Ceremonies',         'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />'],
                    ['name' => 'Church Events',   'desc' => 'Harvest & Anniversaries',   'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />'],
                    ['name' => 'Graduation',      'desc' => 'Celebratory Dinners',       'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14zm-4 6v-7.5l4-2.222" />'],
                    ['name' => 'Private Dining',  'desc' => 'Intimate Gatherings',       'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />'],
                ];
            @endphp

            <div class="grid grid-cols-2 md:grid-cols-4 gap-3 sm:gap-4">
                @foreach($events as $e)
                <a href="{{ route('packages.browse') }}" class="group bg-base-100 rounded-2xl px-4 py-6 flex flex-col items-center text-center hover:shadow-md hover:-translate-y-0.5 transition-all duration-200">
                    <div class="size-12 rounded-xl bg-primary/8 text-primary flex items-center justify-center mb-3 group-hover:bg-primary group-hover:text-white transition-all duration-200">
                        <svg xmlns="http://www.w3.org/2000/svg" class="size-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">{!! $e['icon'] !!}</svg>
                    </div>
                    <h4 class="font-bold text-base-content text-[14px] leading-snug mb-0.5">{{ $e['name'] }}</h4>
                    <p class="text-[11px] text-base-content/50 font-medium">{{ $e['desc'] }}</p>
                </a>
                @endforeach
            </div>
        </div>
    </section>

    {{-- 6. Gallery --}}
    <section class="py-16 sm:py-24 bg-base-100">
        <div class="container mx-auto px-4 lg:px-8">
            <div class="flex flex-col md:flex-row justify-between items-start md:items-end mb-8 sm:mb-12 gap-4">
                <div class="flex flex-col gap-2">
                    <span class="text-[11px] font-bold text-primary uppercase tracking-[0.2em] mb-1 block">In Action</span>
                    <h2 class="text-3xl sm:text-4xl lg:text-5xl font-semibold text-base-content tracking-tight">Our Recent Work</h2>
                </div>
            </div>

            <div class="grid grid-cols-2 md:grid-cols-3 gap-3 sm:gap-4">
                @php
                    $galleryImages = [
                        ['src' => 'https://images.unsplash.com/photo-1519708227418-c8fd9a32b7a2?q=80&w=600', 'alt' => 'Catering setup', 'label' => 'Event Setup'],
                        ['src' => 'https://images.unsplash.com/photo-1414235077428-338989a2e8c0?q=80&w=600', 'alt' => 'Fine dining', 'label' => 'Fine Dining'],
                        ['src' => 'https://images.unsplash.com/photo-1560624052-449f5ddf0c31?q=80&w=600', 'alt' => 'Event catering', 'label' => 'Event Catering'],
                        ['src' => 'https://images.unsplash.com/photo-1481833761820-0509d3217039?q=80&w=600', 'alt' => 'Buffet setup', 'label' => 'Buffet Service'],
                        ['src' => 'https://images.unsplash.com/photo-1547592180-85f173990554?q=80&w=600', 'alt' => 'Food presentation', 'label' => 'Food Presentation'],
                        ['src' => 'https://images.unsplash.com/photo-1505362846-9762db1dbb28?q=80&w=600', 'alt' => 'Corporate event', 'label' => 'Corporate Event'],
                    ];
                @endphp
                @foreach($galleryImages as $img)
                <div class="rounded-2xl overflow-hidden bg-base-200 aspect-square relative group shadow-sm hover:shadow-lg transition-shadow">
                    <img src="{{ $img['src'] }}" alt="{{ $img['alt'] }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700" loading="lazy">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-end p-4">
                        <span class="text-white text-[12px] font-bold uppercase tracking-widest">{{ $img['label'] }}</span>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- 7. Testimonials --}}
    <x-home.testimonials />

    {{-- 8. FAQ --}}
    <x-home.faq />

    {{-- 9. Final CTA Banner --}}
    <section class="py-16 sm:py-24 lg:py-32 bg-primary relative overflow-hidden">
        {{-- Blobs --}}
        <div class="absolute top-0 right-0 size-[500px] bg-white/5 blur-[100px] rounded-full -translate-y-1/3 translate-x-1/4 pointer-events-none"></div>
        <div class="absolute bottom-0 left-0 size-[400px] bg-black/15 blur-[80px] rounded-full translate-y-1/3 -translate-x-1/4 pointer-events-none"></div>
        <div class="absolute top-1/2 left-1/2 size-[700px] bg-white/3 blur-[150px] rounded-full -translate-x-1/2 -translate-y-1/2 pointer-events-none"></div>
        {{-- Crosshatch texture --}}
        <div class="absolute inset-0 bg-[url('data:image/svg+xml,%3Csvg width=\'60\' height=\'60\' viewBox=\'0 0 60 60\' xmlns=\'http://www.w3.org/2000/svg\'%3E%3Cg fill=\'none\' fill-rule=\'evenodd\'%3E%3Cg fill=\'%23ffffff\' fill-opacity=\'0.03\'%3E%3Cpath d=\'M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z\'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E')]" aria-hidden="true"></div>
        {{-- Floating icons --}}
        <div class="absolute top-8 left-12 text-white/8 hidden lg:block -rotate-12" aria-hidden="true">
            <svg xmlns="http://www.w3.org/2000/svg" class="size-24" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="0.8"><path stroke-linecap="round" stroke-linejoin="round" d="M21 15.546c-.523 0-1.046.151-1.5.454a2.704 2.704 0 01-3 0 2.704 2.704 0 00-3 0 2.704 2.704 0 01-3 0 2.704 2.704 0 00-3 0 2.704 2.704 0 01-3 0A1.994 1.994 0 013 15.546M3 19.091c.523 0 1.046-.151 1.5-.454a2.704 2.704 0 013 0 2.704 2.704 0 003 0 2.704 2.704 0 013 0 2.704 2.704 0 003 0 2.704 2.704 0 011.5.454M12 9.75v5.25M12 9.75a2.25 2.25 0 100-4.5 2.25 2.25 0 000 4.5z"/></svg>
        </div>
        <div class="absolute bottom-10 right-12 text-white/6 hidden lg:block rotate-6" aria-hidden="true">
            <svg xmlns="http://www.w3.org/2000/svg" class="size-32" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="0.7"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5.5A2.5 2.5 0 109.5 8H12zm-7 4h14M5 12a2 2 0 110-4h14a2 2 0 110 4M5 12v7a2 2 0 002 2h10a2 2 0 002-2v-7"/></svg>
        </div>
        <div class="absolute top-1/2 left-6 text-white/5 hidden xl:block -translate-y-1/2" aria-hidden="true">
            <svg xmlns="http://www.w3.org/2000/svg" class="size-20" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="0.9"><path stroke-linecap="round" stroke-linejoin="round" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/></svg>
        </div>

        <div class="container mx-auto px-4 lg:px-8 relative z-10 text-center max-w-3xl">
            <div class="inline-flex items-center gap-2 bg-white/15 text-white text-[11px] font-bold px-4 py-2 rounded-full uppercase tracking-widest mb-6">
                <span class="size-2 rounded-full bg-accent animate-pulse"></span>
                Book Your Event Today
            </div>
            <h2 class="text-3xl sm:text-5xl lg:text-6xl font-semibold text-white leading-[1.1] mb-6 tracking-tight">Let us handle the food.<br>You handle the memories.</h2>
            <p class="text-[14px] sm:text-[16px] text-white/70 font-medium mb-8 sm:mb-12">Spots fill up fast — book early to secure your preferred date.</p>

            <div class="flex flex-col sm:flex-row items-center justify-center gap-3 sm:gap-4">
                <a href="{{ route('packages.browse') }}" class="w-full sm:w-auto inline-flex items-center justify-center gap-2.5 bg-white text-primary text-[15px] font-bold px-8 py-4 rounded-xl hover:bg-base-100 hover:scale-105 transition-all shadow-xl shadow-black/20 text-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="size-4.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    Book Your Date Now
                </a>
                <x-ui.whatsapp-button size="lg" variant="white" label="Chat on WhatsApp" class="w-full sm:w-auto hover:scale-105" />
            </div>
        </div>
    </section>

    {{-- Package Details Modal --}}
    <x-package-details-modal />
</div>
