<x-guest-layout title="About Us">
    @php
        $whatsappNumber = \App\Models\Setting::where('key', 'business_whatsapp')->value('value') ?: '233244203181';
    @endphp

    {{-- 1. Hero --}}
    <x-ui.page-hero
        badge="Est. 2018 — Accra, Ghana"
        title="Crafted with Passion,<br class='hidden sm:block'> Served with Excellence"
        subtitle="Diamonds & Pearls Catering is Accra's premier catering partner — bringing authentic Ghanaian flavours and world-class hospitality to every milestone celebration."
    />

    {{-- 2. Our Story --}}
    <section class="py-24 bg-base-100 relative overflow-hidden">
        <div class="absolute top-0 right-0 size-[600px] bg-primary/4 blur-[120px] rounded-full translate-x-1/3 -translate-y-1/4" aria-hidden="true"></div>

        <div class="container mx-auto px-4 lg:px-8 relative z-10">

            {{-- Section Label --}}
            <div class="flex items-center gap-4 mb-16">
                <div class="h-px flex-1 bg-base-content/8"></div>
                <span class="text-[11px] font-bold text-primary uppercase tracking-[0.25em] bg-primary/10 px-4 py-1.5 rounded-full">Our Story</span>
                <div class="h-px flex-1 bg-base-content/8"></div>
            </div>

            <div class="grid lg:grid-cols-12 gap-16 lg:gap-20 items-start">

                {{-- Image column --}}
                <div class="lg:col-span-5 relative">
                    {{-- Main image --}}
                    <div class="aspect-[3/4] rounded-3xl overflow-hidden shadow-2xl">
                        <img src="https://images.unsplash.com/photo-1556910103-1c02745aae4d?q=80&w=800" alt="Chef Grace Ayesu preparing food" class="w-full h-full object-cover" loading="lazy">
                    </div>

                    {{-- Floating stat card --}}
                    <div class="absolute -bottom-6 -right-4 lg:-right-8 bg-primary text-white px-7 py-5 rounded-2xl shadow-2xl">
                        <div class="text-4xl font-bold leading-none mb-1">500+</div>
                        <div class="text-[10px] font-bold uppercase tracking-[0.2em] text-white/70">Events Catered</div>
                    </div>

                    {{-- Floating year badge --}}
                    <div class="absolute -top-5 -left-4 lg:-left-6 bg-[#18542A] text-white px-5 py-3 rounded-xl shadow-xl">
                        <div class="text-[10px] font-bold uppercase tracking-widest text-white/70 mb-0.5">Est.</div>
                        <div class="text-2xl font-bold leading-none">2018</div>
                    </div>
                </div>

                {{-- Text column --}}
                <div class="lg:col-span-7 lg:pt-6">
                    <h2 class="text-4xl lg:text-5xl font-semibold text-base-content tracking-tight mb-10 leading-tight">
                        From a Kitchen<br class="hidden sm:block"> in Accra to<br class="hidden sm:block"> <span class="text-primary">Ghana's Trusted Caterer</span>
                    </h2>

                    <div class="space-y-0">
                        {{-- Timeline items --}}
                        <div class="flex gap-6 pb-10">
                            <div class="flex flex-col items-center shrink-0">
                                <div class="size-10 rounded-full bg-primary text-white flex items-center justify-center font-bold text-[12px] shadow-md">2018</div>
                                <div class="w-px flex-1 bg-base-content/10 mt-2"></div>
                            </div>
                            <div class="pb-2">
                                <h3 class="text-[15px] font-bold text-base-content mb-2">The Beginning</h3>
                                <p class="text-[14px] text-base-content/60 font-medium leading-relaxed">
                                    Executive Chef Grace Ayesu founded Diamonds & Pearls Catering with a bold vision — to bring the rich, authentic flavours of Ghanaian cuisine to every celebration, paired with service that matched the weight of each occasion.
                                </p>
                            </div>
                        </div>

                        <div class="flex gap-6 pb-10">
                            <div class="flex flex-col items-center shrink-0">
                                <div class="size-10 rounded-full bg-[#18542A] text-white flex items-center justify-center font-bold text-[12px] shadow-md">Growth</div>
                                <div class="w-px flex-1 bg-base-content/10 mt-2"></div>
                            </div>
                            <div class="pb-2">
                                <h3 class="text-[15px] font-bold text-base-content mb-2">From Family Tables to Grand Galas</h3>
                                <p class="text-[14px] text-base-content/60 font-medium leading-relaxed">
                                    What started as intimate family events in Accra grew into one of Ghana's most trusted catering services. We have proudly served over 500 events — from grand weddings at Labadi Beach to corporate conferences and funeral celebrations.
                                </p>
                            </div>
                        </div>

                        <div class="flex gap-6">
                            <div class="flex flex-col items-center shrink-0">
                                <div class="size-10 rounded-full bg-accent text-neutral flex items-center justify-center shadow-md">
                                    <svg class="size-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
                                </div>
                            </div>
                            <div>
                                <h3 class="text-[15px] font-bold text-base-content mb-2">Our Philosophy Today</h3>
                                <p class="text-[14px] text-base-content/60 font-medium leading-relaxed">
                                    Source the freshest local ingredients, prepare each dish with care and expertise, and deliver with the precision and warmth that turns a good event into an unforgettable one.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- 3. Mission & Vision --}}
    <section class="py-24 bg-[#18542A] relative overflow-hidden">
        <div class="absolute top-0 right-0 size-[500px] bg-white/5 blur-[100px] rounded-full -translate-y-1/3 translate-x-1/3" aria-hidden="true"></div>
        <div class="absolute bottom-0 left-0 size-[400px] bg-black/15 blur-[80px] rounded-full translate-y-1/3 -translate-x-1/4" aria-hidden="true"></div>
        <div class="absolute top-1/2 left-1/2 size-[600px] bg-white/3 blur-[130px] rounded-full -translate-x-1/2 -translate-y-1/2" aria-hidden="true"></div>
        {{-- Floating icons --}}
        <div class="absolute top-10 left-10 text-white/8 hidden lg:block -rotate-12" aria-hidden="true">
            <svg xmlns="http://www.w3.org/2000/svg" class="size-24" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="0.8"><path stroke-linecap="round" stroke-linejoin="round" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
        </div>
        <div class="absolute bottom-10 right-10 text-white/6 hidden lg:block rotate-6" aria-hidden="true">
            <svg xmlns="http://www.w3.org/2000/svg" class="size-28" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="0.8"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
        </div>
        <div class="absolute top-1/3 right-8 text-white/5 hidden xl:block" aria-hidden="true">
            <svg xmlns="http://www.w3.org/2000/svg" class="size-20" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="0.9"><path stroke-linecap="round" stroke-linejoin="round" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/></svg>
        </div>

        <div class="container mx-auto px-4 lg:px-8 max-w-4xl relative z-10">
            <div class="text-center mb-16">
                <span class="inline-block text-[11px] font-bold text-white/70 uppercase tracking-[0.2em] mb-3 bg-white/10 px-3 py-1 rounded-full">Our Purpose</span>
                <h2 class="text-4xl lg:text-5xl font-semibold text-white tracking-tight mt-4">What Drives Us</h2>
            </div>

            <div class="grid md:grid-cols-2 gap-8">
                <div class="bg-white/10 backdrop-blur-sm p-10 rounded-2xl">
                    <div class="size-12 bg-white/20 text-white rounded-2xl flex items-center justify-center mb-6">
                        <svg xmlns="http://www.w3.org/2000/svg" class="size-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" /></svg>
                    </div>
                    <h3 class="text-xl font-bold text-white mb-3">Our Mission</h3>
                    <p class="text-[15px] text-white/70 font-medium leading-relaxed">
                        To deliver exceptional catering experiences that celebrate Ghanaian culinary heritage while meeting the highest standards of quality, hygiene, and service.
                    </p>
                </div>

                <div class="bg-white/10 backdrop-blur-sm p-10 rounded-2xl">
                    <div class="size-12 bg-white/20 text-white rounded-2xl flex items-center justify-center mb-6">
                        <svg xmlns="http://www.w3.org/2000/svg" class="size-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                    </div>
                    <h3 class="text-xl font-bold text-white mb-3">Our Vision</h3>
                    <p class="text-[15px] text-white/70 font-medium leading-relaxed">
                        To be Ghana's most trusted and sought-after catering partner for every milestone celebration — from naming ceremonies to corporate galas.
                    </p>
                </div>
            </div>
        </div>
    </section>

    {{-- 4. What Sets Us Apart --}}
    <section class="py-24 bg-base-200">
        <div class="container mx-auto px-4 lg:px-8">
            <div class="text-center mb-16">
                <span class="inline-block text-[11px] font-bold text-success uppercase tracking-[0.2em] mb-3 bg-success/10 px-3 py-1 rounded-full">Why Choose Us</span>
                <h2 class="text-4xl lg:text-5xl font-semibold text-base-content tracking-tight mt-4">What Sets Us Apart</h2>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                <div class="bg-success/10 p-8 rounded-2xl shadow-sm hover:shadow-md transition-shadow group">
                    <div class="size-12 bg-success/20 rounded-xl flex items-center justify-center text-success mb-6 group-hover:scale-110 transition-transform">
                        <svg xmlns="http://www.w3.org/2000/svg" class="size-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 10l-2 1m0 0l-2-1m2 1v2.5M20 7l-2 1m2-1l-2-1m2 1v2.5M14 4l-2-1-2 1M4 7l2-1M4 7l2 1M4 7v2.5M12 21l-2-1m2 1l2-1m-2 1v-2.5M6 18l-2-1v-2.5M18 18l2-1v-2.5" /></svg>
                    </div>
                    <h3 class="text-lg font-bold text-base-content mb-2">Fresh Local Ingredients</h3>
                    <p class="text-[13px] text-base-content/60 font-medium leading-relaxed">Sourced daily from local markets to ensure the richest, most authentic Ghanaian taste profiles.</p>
                </div>

                <div class="bg-primary/8 p-8 rounded-2xl shadow-sm hover:shadow-md transition-shadow group">
                    <div class="size-12 bg-primary/15 rounded-xl flex items-center justify-center text-primary mb-6 group-hover:scale-110 transition-transform">
                        <svg xmlns="http://www.w3.org/2000/svg" class="size-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    </div>
                    <h3 class="text-lg font-bold text-base-content mb-2">On-Time Delivery</h3>
                    <p class="text-[13px] text-base-content/60 font-medium leading-relaxed">Our logistics team ensures your food arrives hot, fresh, and exactly when your guests expect it.</p>
                </div>

                <div class="bg-accent/10 p-8 rounded-2xl shadow-sm hover:shadow-md transition-shadow group">
                    <div class="size-12 bg-accent/20 rounded-xl flex items-center justify-center text-amber-600 mb-6 group-hover:scale-110 transition-transform">
                        <svg xmlns="http://www.w3.org/2000/svg" class="size-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" /></svg>
                    </div>
                    <h3 class="text-lg font-bold text-base-content mb-2">Expert Culinary Team</h3>
                    <p class="text-[13px] text-base-content/60 font-medium leading-relaxed">Led by executive chefs with decades of experience in high-volume catering and fine dining.</p>
                </div>

                <div class="bg-success/10 p-8 rounded-2xl shadow-sm hover:shadow-md transition-shadow group">
                    <div class="size-12 bg-success/20 rounded-xl flex items-center justify-center text-success mb-6 group-hover:scale-110 transition-transform">
                        <svg xmlns="http://www.w3.org/2000/svg" class="size-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" /></svg>
                    </div>
                    <h3 class="text-lg font-bold text-base-content mb-2">FDA Certified</h3>
                    <p class="text-[13px] text-base-content/60 font-medium leading-relaxed">Fully approved and adhering to the highest standards of food safety and hygiene in Ghana.</p>
                </div>
            </div>
        </div>
    </section>

    {{-- 5. Testimonials --}}
    <section class="py-16 sm:py-24 bg-base-200">
        <div class="container mx-auto px-4 lg:px-8">
            <div class="text-center mb-10 sm:mb-16">
                <span class="text-[11px] font-bold text-primary uppercase tracking-[0.2em] mb-3 block">Client Stories</span>
                <h2 class="text-3xl sm:text-4xl lg:text-5xl font-semibold text-base-content tracking-tight">What our clients say</h2>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-5 sm:gap-6">
                <x-ui.testimonial-card
                    quote="Diamonds & Pearls made our wedding day absolutely perfect. The food was incredible and every guest kept asking about the caterer. We couldn't be happier."
                    name="Abena & Kweku Mensah"
                    event="Wedding Reception, 2024"
                    initials="AM"
                />
                <x-ui.testimonial-card
                    quote="We hired them for our company's annual dinner and the professionalism was on another level. Hot food, on time, beautifully presented. Will book again."
                    name="Efua Asante"
                    event="Corporate Dinner, 2024"
                />
                <x-ui.testimonial-card
                    quote="From the naming ceremony to the cleanup, everything was flawless. Chef Grace and her team went above and beyond for our family."
                    name="The Boateng Family"
                    event="Naming Ceremony, 2023"
                    initials="BF"
                />
            </div>
        </div>
    </section>

    {{-- 6. Stats Strip --}}
    <section class="py-16 lg:py-20 bg-primary relative overflow-hidden">
        <div class="absolute top-0 right-0 -mr-20 -mt-20 size-[30rem] bg-white/5 rounded-full blur-3xl" aria-hidden="true"></div>
        <div class="absolute bottom-0 left-0 -ml-20 -mb-20 size-[20rem] bg-black/10 rounded-full blur-2xl" aria-hidden="true"></div>

        <div class="container mx-auto px-4 lg:px-8 relative z-10">
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-8 text-center">
                <div>
                    <div class="text-4xl lg:text-5xl font-bold text-white mb-2">500+</div>
                    <div class="text-[10px] font-bold text-white/60 uppercase tracking-[0.2em]">Events Catered</div>
                </div>
                <div>
                    <div class="text-4xl lg:text-5xl font-bold text-white mb-2">6+</div>
                    <div class="text-[10px] font-bold text-white/60 uppercase tracking-[0.2em]">Years Experience</div>
                </div>
                <div>
                    <div class="text-4xl lg:text-5xl font-bold text-white mb-2">98%</div>
                    <div class="text-[10px] font-bold text-white/60 uppercase tracking-[0.2em]">Client Satisfaction</div>
                </div>
                <div>
                    <div class="flex justify-center mb-2">
                        <svg class="size-10 lg:size-12 text-accent" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                    </div>
                    <div class="text-[10px] font-bold text-white/60 uppercase tracking-[0.2em]">FDA Approved</div>
                </div>
            </div>
        </div>
    </section>

    {{-- 7. CTA --}}
    @php
        $socialFacebook = \App\Models\Setting::where('key', 'social_facebook')->value('value');
        $socialInstagram = \App\Models\Setting::where('key', 'social_instagram')->value('value');
        $socialTwitter = \App\Models\Setting::where('key', 'social_twitter')->value('value');
        $socialTiktok = \App\Models\Setting::where('key', 'social_tiktok')->value('value');
    @endphp
    <section class="py-24 lg:py-32 bg-base-200">
        <div class="container mx-auto px-4 lg:px-8 text-center max-w-3xl">
            <h2 class="text-4xl lg:text-5xl font-semibold text-base-content tracking-tight mb-6">Ready to Taste the Difference?</h2>
            <p class="text-[16px] text-base-content/60 font-medium mb-12 leading-relaxed">
                Let us make your next event unforgettable. Browse our packages or chat with us directly to get started.
            </p>
            <div class="flex flex-col sm:flex-row items-center justify-center gap-4 mb-12">
                <x-ui.button href="{{ route('packages.browse') }}" variant="primary" size="lg">
                    View Our Packages
                </x-ui.button>
                <x-ui.whatsapp-button size="lg" label="Chat on WhatsApp" />
            </div>

            {{-- Social Links --}}
            @if($socialFacebook || $socialInstagram || $socialTwitter || $socialTiktok)
                <div class="flex items-center justify-center gap-4">
                    <p class="text-[12px] text-base-content/40 font-medium uppercase tracking-widest">Follow Us</p>
                    @if($socialFacebook)
                        <a href="{{ $socialFacebook }}" target="_blank" class="size-10 bg-base-100 rounded-xl flex items-center justify-center text-base-content/40 hover:text-primary hover:bg-primary/10 transition-all">
                            <svg class="size-5" fill="currentColor" viewBox="0 0 24 24"><path d="M18 2h-3a5 5 0 00-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 011-1h3z"/></svg>
                        </a>
                    @endif
                    @if($socialInstagram)
                        <a href="{{ $socialInstagram }}" target="_blank" class="size-10 bg-base-100 rounded-xl flex items-center justify-center text-base-content/40 hover:text-primary hover:bg-primary/10 transition-all">
                            <svg class="size-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="2" y="2" width="20" height="20" rx="5" ry="5"/><path d="M16 11.37A4 4 0 1112.63 8 4 4 0 0116 11.37z"/><line x1="17.5" y1="6.5" x2="17.51" y2="6.5"/></svg>
                        </a>
                    @endif
                    @if($socialTwitter)
                        <a href="{{ $socialTwitter }}" target="_blank" class="size-10 bg-base-100 rounded-xl flex items-center justify-center text-base-content/40 hover:text-primary hover:bg-primary/10 transition-all">
                            <svg class="size-5" fill="currentColor" viewBox="0 0 24 24"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>
                        </a>
                    @endif
                    @if($socialTiktok)
                        <a href="{{ $socialTiktok }}" target="_blank" class="size-10 bg-base-100 rounded-xl flex items-center justify-center text-base-content/40 hover:text-primary hover:bg-primary/10 transition-all">
                            <svg class="size-5" fill="currentColor" viewBox="0 0 24 24"><path d="M19.59 6.69a4.83 4.83 0 01-3.77-4.25V2h-3.45v13.67a2.89 2.89 0 01-2.88 2.5 2.89 2.89 0 01-2.89-2.89 2.89 2.89 0 012.89-2.89c.28 0 .54.04.79.1V9.01a6.33 6.33 0 00-.79-.05 6.34 6.34 0 00-6.34 6.34 6.34 6.34 0 006.34 6.34 6.34 6.34 0 006.33-6.34V8.69a8.19 8.19 0 004.79 1.54V6.79a4.85 4.85 0 01-1.02-.1z"/></svg>
                        </a>
                    @endif
                </div>
            @endif
        </div>
    </section>

</x-guest-layout>
