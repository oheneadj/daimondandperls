<div
    x-data="{
        showDetails: false,
        selectedPackage: null,
        openDetails(pkg) {
            this.selectedPackage = pkg;
            this.showDetails = true;
        }
    }"
>
    @php
        $whatsappNumber = \App\Models\Setting::where('key', 'business_whatsapp')->value('value') ?: '233244203181';
    @endphp

    {{-- 1. Hero Section --}}
    <section class="relative bg-base-100 min-h-[85vh] flex flex-col justify-center border-b border-base-content/10 overflow-hidden">
        <div class="absolute inset-x-0 top-0 h-full bg-base-200/50">
            <div class="absolute inset-x-0 top-0 h-1/2 bg-gradient-to-b from-primary/5 to-transparent"></div>
        </div>

        <div class="container mx-auto px-4 lg:px-8 relative z-10 pt-20 pb-16 lg:pt-28 lg:pb-24 grid grid-cols-1 lg:grid-cols-12 gap-12 lg:gap-8 items-center">
            <div class="lg:col-span-7">
                <div class="inline-flex items-center gap-2 bg-primary-soft text-primary text-[11px] font-bold px-4 py-2 rounded-full border border-dp-rose-border uppercase tracking-widest mb-6 shadow-sm">
                    <span class="size-2 rounded-full bg-primary animate-pulse"></span>
                    Accra's Finest Catering
                </div>

                <h1 class="text-5xl lg:text-7xl font-semibold text-base-content leading-[1.1] mb-6 tracking-tight">
                    Premium food for every <em class="text-primary italic not-italic font-medium shrink-0">occasion</em>
                </h1>

                <p class="text-lg text-base-content/60 leading-relaxed mb-8 max-w-xl font-medium">
                    Weddings · Corporate Events · Funerals · Naming Ceremonies.<br>
                    Experience authentic Ghanaian flavours and impeccable service.
                </p>

                <div class="flex flex-col sm:flex-row items-center gap-4 mb-12">
                    <x-ui.button href="{{ route('packages.browse') }}" size="lg" class="w-full h-14 sm:w-auto bg-primary text-white text-[15px] font-bold px-8 py-4 !rounded-full hover:bg-primary-hover hover:scale-105 transition-all shadow-md text-center">
                        Order Now
                    </x-ui.button>
                    <x-ui.button href="{{ route('booking.track') }}" variant="black" size="lg" class="w-full h-14 sm:w-auto bg-base-100 text-base-content border border-base-content/10 text-[15px] font-bold px-8 py-4 !rounded-full hover:bg-base-200 hover:scale-105 transition-all shadow-sm text-center">
                        Track Your Booking
                    </x-ui.button>
                </div>

                <div class="flex flex-wrap items-center gap-8 md:gap-12 justify-center sm:justify-start pt-6 border-t border-base-content/10">
                    <div class="text-center sm:text-left">
                        <div class="text-3xl font-bold text-base-content mb-1 tracking-tight">500+</div>
                        <div class="text-[10px] font-bold text-base-content/40 uppercase tracking-widest">Events Served</div>
                    </div>
                    <div class="text-center sm:text-left">
                        <div class="text-3xl font-bold text-base-content mb-1 tracking-tight">10</div>
                        <div class="text-[10px] font-bold text-base-content/40 uppercase tracking-widest">Years Exp.</div>
                    </div>
                    <div class="text-center sm:text-left hidden sm:block">
                        <div class="text-3xl font-bold text-base-content flex justify-center sm:justify-start gap-1 mb-1 tracking-tight">
                            <svg class="size-8 text-success" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                        </div>
                        <div class="text-[10px] font-bold text-base-content/40 uppercase tracking-widest">FDA Approved</div>
                    </div>
                </div>
            </div>

            {{-- Hero Image --}}
            <div class="lg:col-span-5 relative hidden lg:block pl-6">
                <div class="relative w-full aspect-[4/5] rounded-3xl bg-base-200 border border-base-content/5 overflow-hidden shadow-2xl rotate-2 hover:rotate-0 transition-transform duration-500">
                    <img src="https://images.unsplash.com/photo-1547592180-85f173990554?q=80&w=1470" alt="Beautiful catering setup" class="w-full h-full object-cover">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent flex items-end p-8">
                        <div>
                            <div class="inline-flex items-center gap-1.5 bg-white/20 backdrop-blur-md text-white text-[11px] font-bold px-3 py-1.5 mx-0 rounded-lg uppercase tracking-widest mb-3 border border-white/20">
                                Signature Setup
                            </div>
                            <h3 class="text-2xl font-bold text-white leading-tight">Authentic Ghanaian Flavours</h3>
                        </div>
                    </div>
                </div>

                {{-- Floating badge --}}
                <div class="absolute -bottom-6 -left-6 bg-base-100 p-4 rounded-2xl shadow-xl border border-base-content/5 flex items-center gap-4 z-20 hover:scale-105 transition-transform cursor-pointer">
                    <div class="size-12 bg-primary/10 text-primary rounded-xl flex items-center justify-center shrink-0 border border-primary/20">
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
    <div class="bg-primary text-white py-3 px-4 relative shadow-sm" x-data="{ show: true }" x-show="show" x-transition.origin.top>
        <div class="container mx-auto flex items-center justify-center gap-3 md:gap-6 text-[13px] sm:text-[14px]">
            <span class="font-bold flex items-center gap-2 shrink-0">
                <svg xmlns="http://www.w3.org/2000/svg" class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                <span class="hidden sm:inline">Limited Time Offer</span>
            </span>
            <span class="font-medium opacity-95 truncate">Book your event before December and get a complimentary juice bar.</span>
            <a href="{{ route('packages.browse') }}" class="font-bold underline whitespace-nowrap hover:text-white/80 transition-colors">Claim Offer</a>
            <button @click="show = false" class="absolute right-4 top-1/2 -translate-y-1/2 p-1.5 hover:bg-white/10 rounded-lg transition-colors" title="Dismiss">
                <svg xmlns="http://www.w3.org/2000/svg" class="size-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
            </button>
        </div>
    </div>

    {{-- 3. How It Works --}}
    <x-home.how-it-works />

    {{-- 4. Packages & Pricing (Livewire-powered filtering) --}}
    <section id="packages" class="py-24 bg-base-100 border-b border-base-content/10">
        <div class="container mx-auto px-4 lg:px-8">
            <div class="flex flex-col md:flex-row justify-between items-end mb-12 gap-8 border-b border-base-content/10 pb-12">
                <div class="flex flex-col gap-2 relative">
                    <span class="text-[11px] font-bold text-primary uppercase tracking-[0.2em] mb-1 block">Our Offerings</span>
                    <h2 class="text-4xl lg:text-5xl font-semibold text-base-content tracking-tight">Packages & Pricing</h2>
                </div>

                <a href="{{ route('packages.browse') }}" class="inline-flex items-center gap-2 bg-black border border-base-content/10 text-white text-[13px] font-bold px-6 py-3 rounded-full hover:bg-black/80 transition-all shadow-sm">
                    View All Packages
                    <svg xmlns="http://www.w3.org/2000/svg" class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" /></svg>
                </a>
            </div>

            {{-- Category Filter Tabs --}}
            <div class="flex items-center gap-2 overflow-x-auto pb-4 mb-8" style="scrollbar-width: none;">
                <button
                    wire:click="selectCategory(null)"
                    @class([
                        'shrink-0 text-[13px] font-bold px-6 py-2.5 rounded-full transition-all border',
                        'bg-primary text-white border-primary shadow-sm' => is_null($selectedCategory),
                        'bg-base-100 text-base-content border-base-content/10 hover:bg-base-300' => !is_null($selectedCategory),
                    ])
                >
                    All Packages
                </button>
                @foreach($categories as $category)
                    <button
                        wire:click="selectCategory({{ $category->id }})"
                        @class([
                            'shrink-0 text-[13px] font-bold px-6 py-2.5 rounded-full transition-all border whitespace-nowrap',
                            'bg-primary text-white border-primary shadow-sm' => $selectedCategory === $category->id,
                            'bg-base-100 text-base-content border-base-content/10 hover:bg-base-300' => $selectedCategory !== $category->id,
                        ])
                    >
                        {{ $category->name }}
                    </button>
                @endforeach
            </div>

            {{-- Package Cards Grid --}}
            <div wire:loading.class="opacity-50 transition-opacity" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8 items-stretch">
                @forelse($packages as $package)
                    @php $inCart = $cartItems->has($package->id); @endphp
                    <div wire:key="home-pkg-{{ $package->id }}" @click="openDetails({{ json_encode($package) }})">
                        <x-package-card :package="$package" :selected="$inCart" />
                    </div>
                @empty
                    <div class="col-span-full text-center py-16">
                        <p class="text-base-content/40 text-[15px] font-medium">No packages available in this category yet.</p>
                    </div>
                @endforelse
            </div>

            {{-- Custom Menu CTA --}}
            <div class="mt-16 bg-primary border border-primary/20 rounded-2xl p-6 md:p-10 flex flex-col md:flex-row items-center justify-between gap-6 text-center md:text-left">
                <div>
                    <h4 class="text-xl font-bold text-white mb-2">Need a custom menu?</h4>
                    <p class="text-[14px] text-white/60 font-medium">Don't see what you need? We can create a bespoke package tailored for your exact event size and budget.</p>
                </div>
                <a href="https://wa.me/{{ $whatsappNumber }}" target="_blank" class="bg-accent text-black text-[14px] font-bold px-8 py-3.5 rounded-xl hover:bg-primary-hover transition-all shadow-sm shrink-0 whitespace-nowrap">
                    Request Custom Quote
                </a>
            </div>
        </div>
    </section>

    {{-- 5. Event Types --}}
    <section class="py-24 bg-base-200 border-b border-base-content/10">
        <div class="container mx-auto px-4 lg:px-8">
            <div class="text-center mb-16">
                <span class="text-[11px] font-bold text-accent uppercase tracking-[0.2em] mb-3 block">Occasions</span>
                <h2 class="text-4xl lg:text-5xl font-semibold text-base-content tracking-tight">Events we cater for</h2>
            </div>

            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4">
                @php
                    $events = [
                        ['name' => 'Weddings', 'desc' => 'Trad & White', 'img' => 'https://images.unsplash.com/photo-1519225421980-715cb0215aed?q=80&w=400'],
                        ['name' => 'Funerals', 'desc' => 'Large scale', 'img' => 'https://images.unsplash.com/photo-1511795409834-ef04bbd61622?q=80&w=400'],
                        ['name' => 'Corporate', 'desc' => 'Launches & Parties', 'img' => 'https://images.unsplash.com/photo-1505362846-9762db1dbb28?q=80&w=400'],
                        ['name' => 'Birthdays', 'desc' => 'Milestones', 'img' => 'https://images.unsplash.com/photo-1530103862676-de889caef389?q=80&w=400'],
                        ['name' => 'Outdoorings', 'desc' => 'Naming ceremonies', 'img' => 'https://images.unsplash.com/photo-1519671482749-fd09be7ccebf?q=80&w=400'],
                        ['name' => 'Church', 'desc' => 'Harvest & Anniversaries', 'img' => 'https://images.unsplash.com/photo-1438232992991-995b7058bbb3?q=80&w=400'],
                    ];
                @endphp
                @foreach($events as $e)
                <a href="{{ route('packages.browse') }}" class="group relative block aspect-square rounded-2xl overflow-hidden cursor-pointer shadow-sm hover:shadow-xl transition-all border border-base-content/5">
                    <img src="{{ $e['img'] }}" alt="{{ $e['name'] }}" class="absolute inset-0 w-full h-full object-cover group-hover:scale-110 transition-transform duration-700" loading="lazy">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/20 to-transparent"></div>
                    <div class="absolute inset-x-0 bottom-0 p-4 text-center">
                        <h4 class="text-white font-bold text-[15px] mb-0.5">{{ $e['name'] }}</h4>
                        <p class="text-white/60 text-[11px] font-medium">{{ $e['desc'] }}</p>
                    </div>
                </a>
                @endforeach
            </div>
        </div>
    </section>

    {{-- 6. Gallery --}}
    <section class="py-24 bg-base-100 border-b border-base-content/10">
        <div class="container mx-auto px-4 lg:px-8">
            <div class="flex flex-col md:flex-row justify-between items-end mb-12 gap-8">
                <div class="flex flex-col gap-2">
                    <span class="text-[11px] font-bold text-primary uppercase tracking-[0.2em] mb-1 block">In Action</span>
                    <h2 class="text-4xl lg:text-5xl font-semibold text-base-content tracking-tight">Our Recent Work</h2>
                </div>
            </div>

            <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                @php
                    $galleryImages = [
                        ['src' => 'https://images.unsplash.com/photo-1519708227418-c8fd9a32b7a2?q=80&w=600', 'alt' => 'Catering setup'],
                        ['src' => 'https://images.unsplash.com/photo-1414235077428-338989a2e8c0?q=80&w=600', 'alt' => 'Fine dining'],
                        ['src' => 'https://images.unsplash.com/photo-1560624052-449f5ddf0c31?q=80&w=600', 'alt' => 'Event catering'],
                        ['src' => 'https://images.unsplash.com/photo-1481833761820-0509d3217039?q=80&w=600', 'alt' => 'Buffet setup'],
                        ['src' => 'https://images.unsplash.com/photo-1547592180-85f173990554?q=80&w=600', 'alt' => 'Food presentation'],
                        ['src' => 'https://images.unsplash.com/photo-1505362846-9762db1dbb28?q=80&w=600', 'alt' => 'Corporate event'],
                    ];
                @endphp
                @foreach($galleryImages as $img)
                <div class="rounded-2xl overflow-hidden bg-base-200 aspect-square relative group border border-base-content/5">
                    <img src="{{ $img['src'] }}" alt="{{ $img['alt'] }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700" loading="lazy">
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
    <section class="py-24 lg:py-32 bg-primary relative overflow-hidden">
        <div class="absolute top-0 right-0 -mr-20 -mt-20 size-[30rem] bg-white/5 rounded-full blur-3xl"></div>
        <div class="absolute bottom-0 left-0 -ml-20 -mb-20 size-[20rem] bg-black/10 rounded-full blur-2xl"></div>

        <div class="container mx-auto px-4 lg:px-8 relative z-10 text-center max-w-3xl">
            <h2 class="text-5xl lg:text-6xl font-semibold text-white leading-[1.1] mb-6 tracking-tight">Let us handle the food.<br>You handle the memories.</h2>
            <p class="text-[16px] text-white/80 font-medium mb-12">Only 3 weekend slots left for December. Book now to secure your date.</p>

            <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
                <a href="{{ route('packages.browse') }}" class="w-full sm:w-auto bg-white text-primary text-[15px] font-bold px-10 py-5 rounded-xl hover:bg-base-200 hover:scale-105 transition-all shadow-xl">
                    Book Your Date Now
                </a>
                <a href="https://wa.me/{{ $whatsappNumber }}" class="w-full sm:w-auto bg-[#25D366] text-white text-[15px] font-bold px-10 py-5 rounded-xl hover:bg-[#20bd5a] hover:scale-105 transition-all shadow-md flex items-center justify-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="size-5" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M12.031 6.172c-3.181 0-5.767 2.586-5.768 5.766-.001 1.298.38 2.27 1.019 3.287l-.582 2.128 2.182-.573c.978.58 1.911.928 3.145.929 3.178 0 5.767-2.587 5.768-5.766.001-3.187-2.575-5.77-5.764-5.771zm3.392 8.244c-.144.405-.837.774-1.17.824-.299.045-.677.063-1.092-.069-.252-.08-.573-.187-.988-.365-1.739-.751-2.874-2.502-2.961-2.617-.087-.116-.708-.94-.708-1.793s.448-1.273.607-1.446c.159-.173.346-.217.462-.217l.332.006c.106.005.249-.04.39.298.144.347.491 1.2.534 1.287.043.087.072.188.014.304-.058.116-.087.188-.173.289l-.26.304c-.087.086-.177.18-.076.354.101.174.449.741.964 1.201.662.591 1.221.774 1.394.86s.274.072.376-.043c.101-.116.433-.506.549-.68.116-.173.231-.145.39-.087s1.011.477 1.184.564.289.13.332.202c.045.072.045.419-.1.824zm-3.423-14.416c-6.627 0-12 5.373-12 12s5.373 12 12 12 12-5.373 12-12-5.373-12-12-12zm.082 21.183c-1.653 0-3.331-.482-4.717-1.3l-5.365 1.488 1.474-5.26c-.822-1.391-1.309-3.093-1.309-4.821 0-5.319 4.316-9.635 9.636-9.635 5.316 0 9.632 4.316 9.632 9.635 0 5.317-4.316 9.631-9.351 9.631z"/>
                    </svg>
                    Chat on WhatsApp
                </a>
            </div>
        </div>
    </section>

    {{-- Package Details Modal --}}
    <x-package-details-modal />
</div>
