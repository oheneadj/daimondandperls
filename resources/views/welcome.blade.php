<x-guest-layout title="Accra's Premier Catering Service">
    <!-- 1. Hero Section -->
    <section class="relative bg-base-100 min-h-[85vh] flex flex-col justify-center border-b border-base-content/10 overflow-hidden">
        <!-- Background element (simulating food photo overlay) -->
        <div class="absolute inset-x-0 top-0 h-full bg-base-200/50">
            <div class="absolute inset-x-0 top-0 h-1/2 bg-gradient-to-b from-primary/5 to-transparent"></div>
        </div>

        <div class="container mx-auto px-4 lg:px-8 relative z-10 pt-20 pb-16 lg:pt-28 lg:pb-24 grid grid-cols-1 lg:grid-cols-12 gap-12 lg:gap-8 items-center">
            <div class="lg:col-span-7 animate-fade-in-up">
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
                    <x-ui.button href="{{ route('packages.browse') }}" size="lg" class="w-full h-14  sm:w-auto bg-primary text-white text-[15px] font-bold px-8 py-4 !rounded-full hover:bg-primary-hover hover:scale-105 transition-all shadow-md text-center">
                      Order Now
                    </x-ui.button>
                    <x-ui.button href="{{ route('booking.track') }}" variant="black" size="lg" class="w-full h-14  sm:w-auto bg-base-100 text-base-content border border-base-content/10 text-[15px] font-bold px-8 py-4 !rounded-full hover:bg-base-200 hover:scale-105 transition-all shadow-sm text-center">
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

            <!-- Hero Image Composition -->
            <div class="lg:col-span-5 relative hidden lg:block animate-fade-in pl-6">
                <div class="relative w-full aspect-[4/5] rounded-3xl bg-base-200 border border-base-content/5 overflow-hidden shadow-2xl rotate-2 hover:rotate-0 transition-transform duration-500">
                    <img src="https://images.unsplash.com/photo-1547592180-85f173990554?q=80&w=1470" alt="Beautiful catering setup" class="w-full h-full object-cover">
                    <!-- Overlay gradient -->
                    <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent flex items-end p-8">
                        <div>
                            <div class="inline-flex items-center gap-1.5 bg-white/20 backdrop-blur-md text-white text-[11px] font-bold px-3 py-1.5 mx-0 rounded-lg uppercase tracking-widest mb-3 border border-white/20">
                                Signature Setup
                            </div>
                            <h3 class="text-2xl font-bold text-white leading-tight">Authentic Ghanaian Flavours</h3>
                        </div>
                    </div>
                </div>
                
                <!-- Floating badge -->
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

    <!-- 2. Announcement Promo Banner -->
    <div class="bg-primary text-white py-3 px-4 relative shadow-sm" x-data="{ show: true }" x-show="show" x-transition.origin.top>
        <div class="container mx-auto flex items-center justify-center gap-3 md:gap-6 text-[13px] sm:text-[14px]">
            <span class="font-bold flex items-center gap-2 shrink-0">
                <svg xmlns="http://www.w3.org/2000/svg" class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                <span class="hidden sm:inline">Limited Time Offer</span>
            </span>
            <span class="font-medium opacity-95 truncate">Book your event before December and get a complimentary juice bar.</span>
            <a href="#quote" class="font-bold underline whitespace-nowrap hover:text-white/80 transition-colors">Claim Offer</a>
            <button @click="show = false" class="absolute right-4 top-1/2 -translate-y-1/2 p-1.5 hover:bg-white/10 rounded-lg transition-colors" title="Dismiss">
                <svg xmlns="http://www.w3.org/2000/svg" class="size-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
            </button>
        </div>
    </div>
    <!-- 3. Why Choose Us -->
    <section class="py-24 bg-base-100 border-b border-base-content/10">
        <div class="container mx-auto px-4 lg:px-8">
            <div class="text-center max-w-2xl mx-auto mb-16">
                <span class="text-[11px] font-bold text-success uppercase tracking-[0.2em] mb-3 block">Trusted in Accra</span>
                <h2 class="text-4xl lg:text-5xl font-semibold text-base-content tracking-tight mb-4">Why Accra trusts us with their most important days</h2>
                <p class="text-[15px] text-base-content/60 font-medium leading-relaxed">
                    Started in 2018, Diamonds & Pearls has grown into one of Ghana's premier catering services through a commitment to authentic recipes, fresh local ingredients, and flawless execution.
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <div class="bg-base-200 border border-base-content/5 p-8 rounded-2xl hover:bg-base-200-dark transition-colors group">
                    <div class="size-12 bg-white rounded-xl shadow-sm flex items-center justify-center text-success mb-6 group-hover:scale-110 transition-transform">
                        <svg xmlns="http://www.w3.org/2000/svg" class="size-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 10l-2 1m0 0l-2-1m2 1v2.5M20 7l-2 1m2-1l-2-1m2 1v2.5M14 4l-2-1-2 1M4 7l2-1M4 7l2 1M4 7v2.5M12 21l-2-1m2 1l2-1m-2 1v-2.5M6 18l-2-1v-2.5M18 18l2-1v-2.5" /></svg>
                    </div>
                    <h5 class="text-lg font-bold text-base-content mb-2">Fresh Local Ingredients</h5>
                    <p class="text-[13px] text-base-content/60 font-medium leading-relaxed">Sourced daily from local markets to ensure the richest, most authentic Ghanaian taste profiles.</p>
                </div>
                <div class="bg-base-200 border border-base-content/5 p-8 rounded-2xl hover:bg-base-200-dark transition-colors group">
                    <div class="size-12 bg-white rounded-xl shadow-sm flex items-center justify-center text-primary mb-6 group-hover:scale-110 transition-transform">
                        <svg xmlns="http://www.w3.org/2000/svg" class="size-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    </div>
                    <h5 class="text-lg font-bold text-base-content mb-2">On-Time Delivery</h5>
                    <p class="text-[13px] text-base-content/60 font-medium leading-relaxed">Our logistics team ensures your food arrives hot, fresh, and exactly when your guests expect it.</p>
                </div>
                <div class="bg-base-200 border border-base-content/5 p-8 rounded-2xl hover:bg-base-200-dark transition-colors group">
                    <div class="size-12 bg-white rounded-xl shadow-sm flex items-center justify-center text-accent mb-6 group-hover:scale-110 transition-transform">
                        <svg xmlns="http://www.w3.org/2000/svg" class="size-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path d="M12 14l9-5-9-5-9 5 9 5z" /><path d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14zm-4 6v-7.5l4-2.222" /></svg>
                    </div>
                    <h5 class="text-lg font-bold text-base-content mb-2">Expert Culinary Team</h5>
                    <p class="text-[13px] text-base-content/60 font-medium leading-relaxed">Led by executive chefs with decades of experience in high-volume catering and fine dining.</p>
                </div>
                <div class="bg-base-200 border border-base-content/5 p-8 rounded-2xl hover:bg-base-200-dark transition-colors group">
                    <div class="size-12 bg-white rounded-xl shadow-sm flex items-center justify-center text-[#18542A] mb-6 group-hover:scale-110 transition-transform">
                        <svg xmlns="http://www.w3.org/2000/svg" class="size-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" /></svg>
                    </div>
                    <h5 class="text-lg font-bold text-base-content mb-2">Fully Certified</h5>
                    <p class="text-[13px] text-base-content/60 font-medium leading-relaxed">FDA approved and adhering to the highest standards of food safety and hygiene in Ghana.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- 4. Event Types -->
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
                    <img src="{{ $e['img'] }}" alt="{{ $e['name'] }}" class="absolute inset-0 w-full h-full object-cover group-hover:scale-110 transition-transform duration-700">
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

    <!-- 5. Packages & Pricing -->
    <section id="packages" class="py-24 bg-base-100 border-b border-base-content/10">
        <div class="container mx-auto px-4 lg:px-8">
            <div class="flex flex-col md:flex-row justify-between items-end mb-12 gap-8 border-b border-base-content/10 pb-12">
                <div class="flex flex-col gap-2 relative">
                    <span class="text-[11px] font-bold text-primary uppercase tracking-[0.2em] mb-1 block">Our Offerings</span>
                    <h2 class="text-4xl lg:text-5xl font-semibold text-base-content tracking-tight">Packages & Pricing</h2>
                    <svg class="absolute -right-12 -top-6 w-16 h-16 text-accent opacity-20 hidden md:block" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
                </div>
                
                <a href="{{ route('packages.browse') }}" class="inline-flex items-center gap-2 bg-black border border-base-content/10 text-white text-[13px] font-bold px-6 py-3 rounded-full hover:bg-black/80 transition-all shadow-sm">
                    View All {{ \App\Models\Package::count() }} Packages
                    <svg xmlns="http://www.w3.org/2000/svg" class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" /></svg>
                </a>
            </div>

            <livewire:packages.featured-packages />

            <div class="mt-16 bg-primary border border-primary/20 rounded-2xl p-6 md:p-10 flex flex-col md:flex-row items-center justify-between gap-6 text-center md:text-left">
                <div>
                    <h4 class="text-xl font-bold text-white mb-2">Need a custom menu?</h4>
                    <p class="text-[14px] text-white/60 font-medium">Don't see what you need? We can create a bespoke package tailored for your exact event size and budget.</p>
                </div>
                <a href="#quote" class="bg-accent text-black text-[14px] font-bold px-8 py-3.5 rounded-xl hover:bg-primary-hover transition-all shadow-sm shrink-0 whitespace-nowrap">
                    Request Custom Quote
                </a>
            </div>
        </div>
    </section>
    <!-- 7. Menu Showcase -->
    <section class="py-24 bg-base-200 border-b border-base-content/10">
        <div class="container mx-auto px-4 lg:px-8">
            <div class="text-center mb-16">
                <span class="text-[11px] font-bold text-success uppercase tracking-[0.2em] mb-3 block">Culinary Excellence</span>
                <h2 class="text-4xl lg:text-5xl font-semibold text-base-content tracking-tight">Taste of Ghana</h2>
            </div>
            
            <div class="flex items-center gap-4 overflow-x-auto pb-4 mb-8 snap-x" style="scrollbar-width: none;">
                <button class="snap-start shrink-0 bg-primary text-white text-[13px] font-bold px-6 py-2.5 rounded-full shadow-sm">All Dishes</button>
                <button class="snap-start shrink-0 bg-base-100 text-base-content border border-base-content/10 text-[13px] font-bold px-6 py-2.5 rounded-full hover:bg-base-300 transition-colors">Ghanaian</button>
                <button class="snap-start shrink-0 bg-base-100 text-base-content border border-base-content/10 text-[13px] font-bold px-6 py-2.5 rounded-full hover:bg-base-300 transition-colors">Continental</button>
                <button class="snap-start shrink-0 bg-base-100 text-base-content border border-base-content/10 text-[13px] font-bold px-6 py-2.5 rounded-full hover:bg-base-300 transition-colors">Grills & BBQ</button>
                <button class="snap-start shrink-0 bg-base-100 text-base-content border border-base-content/10 text-[13px] font-bold px-6 py-2.5 rounded-full hover:bg-base-300 transition-colors">Desserts</button>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                @php
                    $menuItems = [
                        ['name' => 'Party Jollof Rice', 'desc' => 'Rich, smoky, and perfectly spiced with assorted meat', 'img' => 'https://images.unsplash.com/photo-1604908176997-125f25cc6f3d?q=80&w=400', 'badge' => 'Spicy'],
                        ['name' => 'Waakye Deluxe', 'desc' => 'Served with wele, egg, spaghetti, and shito', 'img' => 'https://images.unsplash.com/photo-1546069901-ba9599a7e63c?q=80&w=400', 'badge' => 'Popular'],
                        ['name' => 'Banku & Tilapia', 'desc' => 'Grilled whole tilapia with hot pepper and onions', 'img' => 'https://images.unsplash.com/photo-1519708227418-c8fd9a32b7a2?q=80&w=400', 'badge' => 'Chef Recommends'],
                        ['name' => 'Fried Yam & Goat', 'desc' => 'Crispy yam chips with succulent spicy goat meat', 'img' => 'https://images.unsplash.com/photo-1628840042765-356cda07504e?q=80&w=400', 'badge' => null],
                    ];
                @endphp
                @foreach($menuItems as $item)
                <div class="bg-base-100 rounded-3xl overflow-hidden border border-base-content/5 shadow-sm hover:shadow-lg transition-all group">
                    <div class="relative h-48 overflow-hidden">
                        <img src="{{ $item['img'] }}" alt="{{ $item['name'] }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                        @if($item['badge'])
                        <div class="absolute top-4 left-4 bg-white/90 backdrop-blur-sm text-base-content text-[10px] font-bold px-3 py-1 rounded-full uppercase tracking-widest shadow-sm">
                            {{ $item['badge'] }}
                        </div>
                        @endif
                    </div>
                    <div class="p-6">
                        <h4 class="text-lg font-bold text-base-content mb-1">{{ $item['name'] }}</h4>
                        <p class="text-[13px] text-base-content/60 font-medium">{{ $item['desc'] }}</p>
                    </div>
                </div>
                @endforeach
            </div>

            <div class="mt-12 text-center">
                <a href="#quote" class="inline-flex items-center gap-2 text-primary font-bold text-[14px] hover:underline underline-offset-4 decoration-2">
                    Download Full Menu PDF
                    <svg xmlns="http://www.w3.org/2000/svg" class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" /></svg>
                </a>
            </div>
        </div>
    </section>

    <!-- 8. Booking Form (Quote Request) -->
    <section id="quote" class="py-24 bg-[#18542A] relative overflow-hidden">
        <div class="absolute inset-0 opacity-10 bg-[radial-gradient(white_1px,transparent_1px)] [background-size:32px_32px]"></div>
        <div class="container mx-auto px-4 lg:px-8 relative z-10">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-16 items-center">
                <div class="lg:col-span-5 text-white">
                    <span class="text-[11px] font-bold text-white/50 uppercase tracking-[0.2em] mb-3 block">Let's talk</span>
                    <h2 class="text-4xl lg:text-5xl font-semibold leading-tight mb-6">Get your free quote in 24 hours</h2>
                    <p class="text-[15px] font-medium text-white/80 leading-relaxed mb-10">
                        Planning an event can be stressful. Getting the food right shouldn't be. Tell us what you need, and we'll handle the rest.
                    </p>
                    <ul class="space-y-4">
                        <li class="flex items-center gap-3">
                            <div class="size-8 bg-white/10 rounded-full flex items-center justify-center shrink-0">
                                <svg class="size-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                            </div>
                            <span class="font-bold text-[14px]">Response within 2 hours</span>
                        </li>
                        <li class="flex items-center gap-3">
                            <div class="size-8 bg-white/10 rounded-full flex items-center justify-center shrink-0">
                                <svg class="size-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                            </div>
                            <span class="font-bold text-[14px]">No hidden fees or charges</span>
                        </li>
                    </ul>
                </div>
                
                <div class="lg:col-span-7">
                    <div class="bg-base-100 rounded-3xl shadow-2xl p-8 lg:p-10 relative">
                        <!-- Redirecting to checkout for real functionality -->
                        <form action="{{ route('checkout') }}" method="GET" class="space-y-6">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-[11px] font-bold text-base-content/40 uppercase tracking-widest mb-2">Event Type</label>
                                    <select class="w-full bg-base-200 border border-base-content/10 rounded-xl px-4 py-3.5 text-[14px] font-medium text-base-content focus:border-primary focus:ring focus:ring-primary/20 transition-all outline-none">
                                        <option>Wedding</option>
                                        <option>Funeral</option>
                                        <option>Corporate Event</option>
                                        <option>Birthday Party</option>
                                        <option>Other</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-[11px] font-bold text-base-content/40 uppercase tracking-widest mb-2">Estimated Guests</label>
                                    <select class="w-full bg-base-200 border border-base-content/10 rounded-xl px-4 py-3.5 text-[14px] font-medium text-base-content focus:border-primary focus:ring focus:ring-primary/20 transition-all outline-none">
                                        <option>50 - 100</option>
                                        <option>100 - 250</option>
                                        <option>250 - 500</option>
                                        <option>500+</option>
                                    </select>
                                </div>
                            </div>
                            
                            <div>
                                <label class="block text-[11px] font-bold text-base-content/40 uppercase tracking-widest mb-2">Full Name</label>
                                <input type="text" placeholder="e.g. Ama Mensah" class="w-full bg-base-200 border border-base-content/10 rounded-xl px-4 py-3.5 text-[14px] font-medium text-base-content focus:border-primary focus:ring focus:ring-primary/20 transition-all outline-none">
                            </div>

                            <div class="flex flex-col sm:flex-row items-center gap-4 pt-4 border-t border-base-content/10">
                                <button type="submit" class="w-full sm:w-auto bg-primary text-white text-[15px] font-bold px-8 py-4 rounded-xl hover:bg-primary-hover shadow-md transition-all">
                                    Start Booking Now
                                </button>
                                <span class="text-base-content/40 font-bold text-[13px]">OR</span>
                                <a href="https://wa.me/233244203181" target="_blank" class="w-full sm:w-auto bg-[#25D366] text-white text-[15px] font-bold px-8 py-4 rounded-xl hover:bg-[#20bd5a] shadow-sm transition-all flex items-center justify-center gap-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="size-5" viewBox="0 0 24 24" fill="currentColor">
                                        <path d="M12.031 6.172c-3.181 0-5.767 2.586-5.768 5.766-.001 1.298.38 2.27 1.019 3.287l-.582 2.128 2.182-.573c.978.58 1.911.928 3.145.929 3.178 0 5.767-2.587 5.768-5.766.001-3.187-2.575-5.77-5.764-5.771zm3.392 8.244c-.144.405-.837.774-1.17.824-.299.045-.677.063-1.092-.069-.252-.08-.573-.187-.988-.365-1.739-.751-2.874-2.502-2.961-2.617-.087-.116-.708-.94-.708-1.793s.448-1.273.607-1.446c.159-.173.346-.217.462-.217l.332.006c.106.005.249-.04.39.298.144.347.491 1.2.534 1.287.043.087.072.188.014.304-.058.116-.087.188-.173.289l-.26.304c-.087.086-.177.18-.076.354.101.174.449.741.964 1.201.662.591 1.221.774 1.394.86s.274.072.376-.043c.101-.116.433-.506.549-.68.116-.173.231-.145.39-.087s1.011.477 1.184.564.289.13.332.202c.045.072.045.419-.1.824zm-3.423-14.416c-6.627 0-12 5.373-12 12s5.373 12 12 12 12-5.373 12-12-5.373-12-12-12zm.082 21.183c-1.653 0-3.331-.482-4.717-1.3l-5.365 1.488 1.474-5.26c-.822-1.391-1.309-3.093-1.309-4.821 0-5.319 4.316-9.635 9.636-9.635 5.316 0 9.632 4.316 9.632 9.635 0 5.317-4.316 9.631-9.351 9.631z"/>
                                    </svg>
                                    Chat on WhatsApp
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- 9. Photo & Video Gallery -->
    <section class="py-24 bg-base-100 border-b border-base-content/10">
        <div class="container mx-auto px-4 lg:px-8">
            <div class="flex flex-col md:flex-row justify-between items-end mb-12 gap-8 pb-12">
                <div class="flex flex-col gap-2">
                    <span class="text-[11px] font-bold text-primary uppercase tracking-[0.2em] mb-1 block">In Action</span>
                    <h2 class="text-4xl lg:text-5xl font-semibold text-base-content tracking-tight">Our Recent Work</h2>
                </div>
                
                <a href="#" class="inline-flex items-center gap-2 text-[14px] font-bold bg-base-200 border border-base-content/10 px-6 py-3 rounded-xl hover:bg-base-300 transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" class="size-5 text-[#E1306C]" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M11.999 7.377a4.623 4.623 0 1 0 0 9.248 4.623 4.623 0 0 0 0-9.248zm0 7.627a3.004 3.004 0 1 1 0-6.008 3.004 3.004 0 0 1 0 6.008z"/>
                        <circle cx="16.806" cy="7.207" r="1.078"/>
                        <path d="M20.533 6.111A4.605 4.605 0 0 0 17.9 3.479a6.606 6.606 0 0 0-2.186-.42c-.963-.042-1.268-.054-3.71-.054s-2.755 0-3.71.054a6.554 6.554 0 0 0-2.184.42 4.6 4.6 0 0 0-2.633 2.632 6.585 6.585 0 0 0-.419 2.186c-.043.962-.056 1.267-.056 3.71 0 2.442 0 2.753.056 3.71.015.748.156 1.486.419 2.187a4.61 4.61 0 0 0 2.634 2.632 6.584 6.584 0 0 0 2.185.45c.963.042 1.268.055 3.71.055s2.755 0 3.71-.055a6.615 6.615 0 0 0 2.186-.419 4.613 4.613 0 0 0 2.633-2.633c.263-.7.404-1.438.419-2.186.043-.962.056-1.267.056-3.71s0-2.753-.056-3.71a6.581 6.581 0 0 0-.421-2.217zm-1.218 9.532a5.043 5.043 0 0 1-.311 1.688 2.987 2.987 0 0 1-1.712 1.711 4.985 4.985 0 0 1-1.67.311c-.95.044-1.218.055-3.654.055-2.438 0-2.687 0-3.655-.055a4.96 4.96 0 0 1-1.669-.311 2.985 2.985 0 0 1-1.719-1.711 5.08 5.08 0 0 1-.311-1.669c-.043-.95-.053-1.218-.053-3.654 0-2.437 0-2.686.053-3.655a5.038 5.038 0 0 1 .311-1.687c.305-.789.93-1.41 1.719-1.712a5.01 5.01 0 0 1 1.669-.311c.952-.043 1.218-.055 3.655-.055s2.687 0 3.654.055a4.96 4.96 0 0 1 1.67.311 2.991 2.991 0 0 1 1.712 1.712 5.08 5.08 0 0 1 .311 1.669c.043.951.054 1.218.054 3.655 0 2.436 0 2.698-.043 3.654h-.011z"/>
                    </svg>
                    Follow our updates
                </a>
            </div>

            <!-- Simple Grid implementation of Masonry -->
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 auto-rows-[200px]">
                <div class="rounded-2xl overflow-hidden bg-base-200 lg:col-span-2 lg:row-span-2 relative group cursor-pointer block border border-base-content/5">
                    <img src="https://images.unsplash.com/photo-1519708227418-c8fd9a32b7a2?q=80&w=800" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700">
                    <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="size-10 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7" /></svg>
                    </div>
                </div>
                <div class="rounded-2xl overflow-hidden bg-base-200 relative group cursor-pointer block border border-base-content/5">
                    <img src="https://images.unsplash.com/photo-1414235077428-338989a2e8c0?q=80&w=400" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700">
                </div>
                <div class="rounded-2xl overflow-hidden bg-base-200 lg:row-span-2 relative group cursor-pointer block border border-base-content/5">
                    <img src="https://images.unsplash.com/photo-1560624052-449f5ddf0c31?q=80&w=400" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700">
                </div>
                <div class="rounded-2xl overflow-hidden bg-base-200 relative group cursor-pointer block border border-base-content/5">
                    <img src="https://images.unsplash.com/photo-1481833761820-0509d3217039?q=80&w=400" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700">
                </div>
            </div>
            
        </div>
    </section>

    <!-- 10. Testimonials & Reviews -->
    <section class="py-24 bg-base-200 border-b border-base-content/10">
        <div class="container mx-auto px-4 lg:px-8">
            <div class="text-center mb-16">
                <span class="text-[11px] font-bold text-accent uppercase tracking-[0.2em] mb-3 block">Word of mouth</span>
                <h2 class="text-4xl lg:text-5xl font-semibold text-base-content tracking-tight">What our clients say</h2>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @php
                    $reviews = [
                        ['name' => 'Ama Mensah', 'event' => 'Wedding, Labadi', 'text' => "The food was incredible. Every guest was asking who the caterer was. The local dishes tasted exactly like my grandmother's cooking, just elevated!"],
                        ['name' => 'Kojo Asante', 'event' => 'Corporate Retreat', 'text' => "Diamonds & Pearls handled our 200-person retreat flawlessly. Setup was on time, staff were professional, and the continental menu was perfect."],
                        ['name' => 'Efua Boakye', 'event' => 'Outdooring', 'text' => "I was so stressed about food for my baby's outdooring, but they took over everything. The Jollof and goat meat was the highlight of the day."],
                    ];
                @endphp
                @foreach($reviews as $review)
                <div class="bg-base-100 p-8 rounded-3xl border border-base-content/5 shadow-sm hover:shadow-lg transition-all relative">
                    <div class="absolute -top-4 right-8 bg-[#f5b800] text-white px-3 py-1 rounded-full flex gap-1 items-center shadow-md">
                        @for($i=0; $i<5; $i++)
                        <svg class="size-3" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                        @endfor
                    </div>
                    <svg class="size-10 text-primary/10 mb-6" fill="currentColor" viewBox="0 0 24 24"><path d="M14.017 21v-7.391c0-5.704 3.731-9.57 8.983-10.609l.995 2.151c-2.432.917-3.995 3.638-3.995 5.849h4v10h-9.983zm-14.017 0v-7.391c0-5.704 3.748-9.57 9-10.609l.996 2.151c-2.433.917-3.996 3.638-3.996 5.849h3.983v10h-9.983z"/></svg>
                    <p class="text-[14px] leading-relaxed text-base-content/80 font-medium mb-8">"{{ $review['text'] }}"</p>
                    <div class="flex items-center gap-4">
                        <div class="size-10 bg-base-200 rounded-full flex items-center justify-center font-bold text-base-content/50 border border-base-content/10">
                            {{ substr($review['name'], 0, 1) }}
                        </div>
                        <div>
                            <div class="text-[14px] font-bold text-base-content">{{ $review['name'] }}</div>
                            <div class="text-[11px] font-bold text-primary uppercase tracking-widest">{{ $review['event'] }}</div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </section>
    <!-- 11. How the Booking Process Works -->
    <section class="py-24 bg-base-100 border-b border-base-content/10">
        <div class="container mx-auto px-4 lg:px-8">
            <div class="text-center mb-16">
                <span class="text-[11px] font-bold text-primary uppercase tracking-[0.2em] mb-3 block">Simple Process</span>
                <h2 class="text-4xl lg:text-5xl font-semibold text-base-content tracking-tight">How it works</h2>
            </div>
            
            <div class="relative max-w-5xl mx-auto">
                <!-- Connecting Line (Desktop) -->
                <div class="hidden md:block absolute top-[4.5rem] left-0 w-full h-0.5 bg-base-content/5 z-0"></div>
                
                <div class="grid grid-cols-1 md:grid-cols-4 gap-8 md:gap-4 relative z-10">
                    <div class="text-center group">
                        <div class="size-16 mx-auto bg-base-100 border-2 border-primary/20 text-primary rounded-2xl flex items-center justify-center font-bold text-xl mb-6 shadow-sm group-hover:bg-primary group-hover:text-white group-hover:border-primary transition-all duration-300">
                            1
                        </div>
                        <h4 class="text-lg font-bold text-base-content mb-2">Choose Package</h4>
                        <p class="text-[13px] text-base-content/60 font-medium px-4">Browse our curated packages and find the perfect match for your event size and budget.</p>
                    </div>
                    <div class="text-center group">
                        <div class="size-16 mx-auto bg-base-100 border-2 border-primary/20 text-primary rounded-2xl flex items-center justify-center font-bold text-xl mb-6 shadow-sm group-hover:bg-primary group-hover:text-white group-hover:border-primary transition-all duration-300">
                            2
                        </div>
                        <h4 class="text-lg font-bold text-base-content mb-2">Book Online</h4>
                        <p class="text-[13px] text-base-content/60 font-medium px-4">Fill out your event details, location, and guest count securely on our platform.</p>
                    </div>
                    <div class="text-center group">
                        <div class="size-16 mx-auto bg-base-100 border-2 border-primary/20 text-primary rounded-2xl flex items-center justify-center font-bold text-xl mb-6 shadow-sm group-hover:bg-primary group-hover:text-white group-hover:border-primary transition-all duration-300">
                            3
                        </div>
                        <h4 class="text-lg font-bold text-base-content mb-2">Pay Deposit</h4>
                        <p class="text-[13px] text-base-content/60 font-medium px-4">Secure your date instantly with a Momo or card payment. Minimum deposit rules apply.</p>
                    </div>
                    <div class="text-center group">
                        <div class="size-16 mx-auto bg-base-100 border-2 border-primary/20 text-primary rounded-2xl flex items-center justify-center font-bold text-xl mb-6 shadow-sm group-hover:bg-primary group-hover:text-white group-hover:border-primary transition-all duration-300">
                            4
                        </div>
                        <h4 class="text-lg font-bold text-base-content mb-2">We Deliver</h4>
                        <p class="text-[13px] text-base-content/60 font-medium px-4">Our team arrives early to set up, serve, and clean up. You enjoy your special day.</p>
                    </div>
                </div>

                <div class="mt-16 flex flex-wrap justify-center items-center gap-6 opacity-60 grayscale hover:grayscale-0 transition-all duration-500">
                    <span class="text-[11px] font-bold text-base-content uppercase tracking-widest">Accepted Payments:</span>
                    <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/1/14/MTN_Logo.svg/1024px-MTN_Logo.svg.png" class="h-8 object-contain mix-blend-multiply" alt="MTN Momo">
                    <img src="https://logos-world.net/wp-content/uploads/2020/09/Mastercard-Logo-2016-1024x576.png" class="h-8 object-contain mix-blend-multiply" alt="Mastercard">
                    <img src="https://logos-world.net/wp-content/uploads/2020/04/Visa-Logo-2014-present-800x450.png" class="h-8 object-contain mix-blend-multiply" alt="Visa">
                </div>
            </div>
        </div>
    </section>

    <!-- 12. About Us Snapshot -->
    <section class="py-24 bg-base-200 border-b border-base-content/10">
        <div class="container mx-auto px-4 lg:px-8">
            <div class="bg-base-100 rounded-[2.5rem] shadow-xl overflow-hidden border border-base-content/5">
                <div class="grid grid-cols-1 lg:grid-cols-2">
                    <div class="p-10 lg:p-16 flex flex-col justify-center">
                        <span class="text-[11px] font-bold text-primary uppercase tracking-[0.2em] mb-4 block">Our Story</span>
                        <h3 class="text-3xl lg:text-4xl font-bold text-base-content mb-6 leading-tight">Serving love from our kitchen to your table</h3>
                        <p class="text-[15px] font-medium text-base-content/70 leading-relaxed mb-8">
                            Founded by Executive Chef Grace Ayesu in 2018, Diamonds & Pearls Catering started as a small, passionate operation in Accra. Today, we are proud to be the trusted culinary partner for hundreds of families and businesses across Ghana.
                        </p>
                        <p class="text-[15px] font-medium text-base-content/70 leading-relaxed mb-10">
                            Our mission is simple: to bring the rich, authentic flavours of Ghanaian cuisine to every event, paired with world-class hospitality and professionalism.
                        </p>
                        <div>
                            <a href="{{ route('about') }}" class="inline-flex items-center gap-2 text-primary font-bold text-[14px] hover:text-primary-hover group">
                                Read Our Full Story
                                <svg xmlns="http://www.w3.org/2000/svg" class="size-4 group-hover:translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                            </a>
                        </div>
                    </div>
                    <div class="relative h-80 lg:h-auto">
                        <img src="https://images.unsplash.com/photo-1577219491135-ce391730fb2c?q=80&w=800" alt="Chef preparing food" class="absolute inset-0 w-full h-full object-cover">
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- 13. Clients & Partners Logos -->
    <section class="py-16 bg-base-100 border-b border-base-content/10 overflow-hidden">
        <div class="container mx-auto px-4 lg:px-8">
            <div class="text-center mb-8">
                <h4 class="text-[11px] font-bold text-base-content/40 uppercase tracking-[0.2em]">Trusted by Accra's finest organizations</h4>
            </div>
            <!-- Auto-scrolling logo strip (CSS animation implementation) -->
            <div class="flex items-center justify-center flex-wrap gap-8 md:gap-16 opacity-50 grayscale">
                <!-- Placeholder text logos for now -->
                <span class="text-xl font-bold font-serif">Stanbic Bank</span>
                <span class="text-xl font-bold uppercase tracking-wider">MTN Ghana</span>
                <span class="text-xl font-bold italic">Kempinski</span>
                <span class="text-xl font-bold">Tullow Oil</span>
                <span class="text-xl font-bold font-serif">University of Ghana</span>
            </div>
        </div>
    </section>

    <!-- 14. FAQ -->
    <section class="py-24 bg-base-200 border-b border-base-content/10">
        <div class="container mx-auto px-4 lg:px-8 max-w-4xl">
            <div class="text-center mb-16">
                <span class="text-[11px] font-bold text-accent uppercase tracking-[0.2em] mb-3 block">Got Questions?</span>
                <h2 class="text-4xl lg:text-5xl font-semibold text-base-content tracking-tight">Frequently Asked Questions</h2>
            </div>

            <div class="space-y-4" x-data="{ active: null }">
                @php
                    $faqs = [
                        ['q' => 'Do you cater outside Accra?', 'a' => 'Yes, we cater events across the Greater Accra region, Tema, and parts of the Eastern Region. Travel fees may apply for locations outside central Accra.'],
                        ['q' => 'What is your minimum order size?', 'a' => 'Our standard minimum order is for 50 guests. For smaller VIP or private dining events, please contact us for a custom quote.'],
                        ['q' => 'Do you provide plates, cutlery and chafing dishes?', 'a' => 'Yes, all our standard and premium packages include standard chafing dishes, serving utensils, and necessary catering equipment. Premium tableware can be rented separately.'],
                        ['q' => 'Can I customize my menu?', 'a' => 'Absolutely. While our set packages are most popular, we are happy to swap items or create a completely bespoke menu tailored to your cultural or dietary needs.'],
                        ['q' => 'How far in advance should I book?', 'a' => 'We recommend booking at least 3 weeks in advance, especially for weekend events and during the festive season (December).'],
                    ];
                @endphp
                
                @foreach($faqs as $index => $faq)
                <div class="bg-base-100 border border-base-content/10 rounded-2xl overflow-hidden transition-all duration-300">
                    <button class="w-full px-6 py-5 text-left flex justify-between items-center bg-base-100 hover:bg-base-200 focus:outline-none" @click="active === {{ $index }} ? active = null : active = {{ $index }}">
                        <span class="font-bold text-base-content text-[15px] pr-8">{{ $faq['q'] }}</span>
                        <svg class="size-5 text-primary transform transition-transform duration-300 shrink-0" :class="{'rotate-180': active === {{ $index }}}" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                    </button>
                    <div class="px-6 py-5 bg-base-100/50 border-t border-base-content/5" x-show="active === {{ $index }}" x-collapse x-cloak style="display: none;">
                        <p class="text-[14px] text-base-content/70 font-medium leading-relaxed">{{ $faq['a'] }}</p>
                    </div>
                </div>
                @endforeach
            </div>
            
            <div class="text-center mt-12">
                <p class="text-[14px] font-medium text-base-content/60">
                    Still have questions? <a href="https://wa.me/233244203181" class="text-primary font-bold hover:underline">Chat with us on WhatsApp</a>
                </p>
            </div>
        </div>
    </section>

    <!-- 15. Blog / Tips & Recipes Placeholder -->
    <section class="py-24 bg-base-100 border-b border-base-content/10">
        <div class="container mx-auto px-4 lg:px-8">
            <div class="flex flex-col md:flex-row justify-between items-end mb-12 gap-8 pb-12">
                <div class="flex flex-col gap-2">
                    <span class="text-[11px] font-bold text-success uppercase tracking-[0.2em] mb-1 block">Articles</span>
                    <h2 class="text-4xl lg:text-5xl font-semibold text-base-content tracking-tight">Catering Tips & News</h2>
                </div>
                
                <a href="#" class="inline-flex items-center gap-2 text-base-content/60 font-bold text-[14px] hover:text-primary transition-colors">
                    Read all articles
                    <svg xmlns="http://www.w3.org/2000/svg" class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" /></svg>
                </a>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <a href="#" class="group block">
                    <div class="rounded-2xl overflow-hidden aspect-[3/2] mb-4 bg-base-200 border border-base-content/5">
                        <img src="https://images.unsplash.com/photo-1519708227418-c8fd9a32b7a2?q=80&w=400" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                    </div>
                    <div class="flex items-center gap-3 text-[11px] font-bold text-base-content/40 uppercase tracking-widest mb-2">
                        <span>Event Planning</span>
                        <span class="w-1 h-1 rounded-full bg-base-content/20"></span>
                        <span>Nov 12, 2025</span>
                    </div>
                    <h4 class="text-xl font-bold text-base-content group-hover:text-primary transition-colors mb-2">5 Ghanaian Dishes Every Wedding Needs</h4>
                    <p class="text-[14px] text-base-content/60 font-medium line-clamp-2">Ensure your guests have the best experience by including these staple cultural dishes in your buffet.</p>
                </a>
                <a href="#" class="group block">
                    <div class="rounded-2xl overflow-hidden aspect-[3/2] mb-4 bg-base-200 border border-base-content/5">
                        <img src="https://images.unsplash.com/photo-1414235077428-338989a2e8c0?q=80&w=400" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                    </div>
                    <div class="flex items-center gap-3 text-[11px] font-bold text-base-content/40 uppercase tracking-widest mb-2">
                        <span>Budgeting</span>
                        <span class="w-1 h-1 rounded-full bg-base-content/20"></span>
                        <span>Oct 05, 2025</span>
                    </div>
                    <h4 class="text-xl font-bold text-base-content group-hover:text-primary transition-colors mb-2">How to Plan a Budget Funeral Catering</h4>
                    <p class="text-[14px] text-base-content/60 font-medium line-clamp-2">Funerals in Ghana can be expensive. Here is how to feed a large crowd without compromising on quality.</p>
                </a>
                <a href="#" class="group block">
                    <div class="rounded-2xl overflow-hidden aspect-[3/2] mb-4 bg-base-200 border border-base-content/5">
                        <img src="https://images.unsplash.com/photo-1505362846-9762db1dbb28?q=80&w=400" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                    </div>
                    <div class="flex items-center gap-3 text-[11px] font-bold text-base-content/40 uppercase tracking-widest mb-2">
                        <span>Corporate</span>
                        <span class="w-1 h-1 rounded-full bg-base-content/20"></span>
                        <span>Sep 28, 2025</span>
                    </div>
                    <h4 class="text-xl font-bold text-base-content group-hover:text-primary transition-colors mb-2">Level Up Your Next Office Retreat</h4>
                    <p class="text-[14px] text-base-content/60 font-medium line-clamp-2">Impress your staff and clients with our specially designed continental and local fusion business packages.</p>
                </a>
            </div>
        </div>
    </section>

    <!-- 16. Final CTA Banner -->
    <section class="py-24 lg:py-32 bg-primary relative overflow-hidden">
        <!-- Abstract shapes -->
        <div class="absolute top-0 right-0 -mr-20 -mt-20 size-[30rem] bg-white/5 rounded-full blur-3xl rounded-full"></div>
        <div class="absolute bottom-0 left-0 -ml-20 -mb-20 size-[20rem] bg-black/10 rounded-full blur-2xl rounded-full"></div>
        
        <div class="container mx-auto px-4 lg:px-8 relative z-10 text-center max-w-3xl">
            <h2 class="text-5xl lg:text-6xl font-semibold text-white leading-[1.1] mb-6 tracking-tight">Let us handle the food.<br>You handle the memories.</h2>
            <p class="text-[16px] text-white/80 font-medium mb-12">Only 3 weekend slots left for December. Book now to secure your date.</p>
            
            <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
                <a href="{{ route('packages.browse') }}" class="w-full sm:w-auto bg-white text-primary text-[15px] font-bold px-10 py-5 rounded-xl hover:bg-base-200 hover:scale-105 transition-all shadow-xl">
                    Book Your Date Now
                </a>
                <a href="https://wa.me/233244203181" class="w-full sm:w-auto bg-[#25D366] text-white text-[15px] font-bold px-10 py-5 rounded-xl hover:bg-[#20bd5a] hover:scale-105 transition-all shadow-md flex items-center justify-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="size-5" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M12.031 6.172c-3.181 0-5.767 2.586-5.768 5.766-.001 1.298.38 2.27 1.019 3.287l-.582 2.128 2.182-.573c.978.58 1.911.928 3.145.929 3.178 0 5.767-2.587 5.768-5.766.001-3.187-2.575-5.77-5.764-5.771zm3.392 8.244c-.144.405-.837.774-1.17.824-.299.045-.677.063-1.092-.069-.252-.08-.573-.187-.988-.365-1.739-.751-2.874-2.502-2.961-2.617-.087-.116-.708-.94-.708-1.793s.448-1.273.607-1.446c.159-.173.346-.217.462-.217l.332.006c.106.005.249-.04.39.298.144.347.491 1.2.534 1.287.043.087.072.188.014.304-.058.116-.087.188-.173.289l-.26.304c-.087.086-.177.18-.076.354.101.174.449.741.964 1.201.662.591 1.221.774 1.394.86s.274.072.376-.043c.101-.116.433-.506.549-.68.116-.173.231-.145.39-.087s1.011.477 1.184.564.289.13.332.202c.045.072.045.419-.1.824zm-3.423-14.416c-6.627 0-12 5.373-12 12s5.373 12 12 12 12-5.373 12-12-5.373-12-12-12zm.082 21.183c-1.653 0-3.331-.482-4.717-1.3l-5.365 1.488 1.474-5.26c-.822-1.391-1.309-3.093-1.309-4.821 0-5.319 4.316-9.635 9.636-9.635 5.316 0 9.632 4.316 9.632 9.635 0 5.317-4.316 9.631-9.351 9.631z"/>
                    </svg>
                    Chat on WhatsApp
                </a>
            </div>
        </div>
    </section>
</x-guest-layout>
