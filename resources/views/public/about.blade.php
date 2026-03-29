<x-guest-layout title="About Us">
    @php
        $whatsappNumber = \App\Models\Setting::where('key', 'business_whatsapp')->value('value') ?: '233244203181';
    @endphp

    {{-- 1. Hero --}}
    <section class="relative bg-base-200 py-20 lg:py-32 overflow-hidden border-b border-base-content/10">
        <div class="absolute top-0 right-0 size-[500px] bg-primary/5 blur-[120px] rounded-full -translate-y-1/2 translate-x-1/4"></div>
        <div class="absolute bottom-0 left-0 size-[400px] bg-accent/5 blur-[120px] rounded-full translate-y-1/2 -translate-x-1/4"></div>

        <div class="container mx-auto px-4 lg:px-8 text-center relative z-10">
            <div class="inline-flex items-center gap-2 bg-primary-soft text-primary text-[11px] font-bold px-4 py-2 rounded-full border border-dp-rose-border uppercase tracking-widest mb-6 shadow-sm">
                <span class="size-2 rounded-full bg-primary animate-pulse"></span>
                Est. 2018 &mdash; Accra, Ghana
            </div>

            <h1 class="text-5xl lg:text-7xl font-semibold text-base-content tracking-tight mb-6">Crafted with Passion,<br class="hidden sm:block"> Served with Excellence</h1>

            <p class="text-lg text-base-content/60 font-medium max-w-2xl mx-auto leading-relaxed">
                Diamonds & Pearls Catering is Accra's premier catering partner — bringing authentic Ghanaian flavours and world-class hospitality to every milestone celebration.
            </p>
        </div>
    </section>

    {{-- 2. Our Story --}}
    <section class="py-24 bg-base-100 border-b border-base-content/10">
        <div class="container mx-auto px-4 lg:px-8">
            <div class="grid lg:grid-cols-2 gap-16 lg:gap-20 items-center">
                <div>
                    <span class="text-[11px] font-bold text-primary uppercase tracking-[0.2em] mb-4 block">Our Story</span>
                    <h2 class="text-4xl lg:text-5xl font-semibold text-base-content tracking-tight mb-8 leading-tight">From a Kitchen in Accra to Ghana's Trusted Caterer</h2>

                    <div class="space-y-6 text-[15px] text-base-content/60 font-medium leading-relaxed">
                        <p>
                            In 2018, Executive Chef Grace Ayesu started Diamonds & Pearls Catering with a vision that was both simple and bold: to bring the rich, authentic flavours of Ghanaian cuisine to every celebration, paired with a level of service that matched the importance of the occasion.
                        </p>
                        <p>
                            What began as a small operation serving intimate family events in Accra has grown into one of Ghana's most trusted catering services. Today, we have proudly catered over 500 events — from grand weddings at Labadi Beach to corporate conferences, funeral celebrations, and everything in between.
                        </p>
                        <p>
                            Our philosophy remains unchanged: source the freshest local ingredients, prepare each dish with care and expertise, and deliver with the kind of precision and warmth that turns a good event into an unforgettable one.
                        </p>
                    </div>
                </div>

                <div class="relative">
                    <div class="aspect-[4/5] rounded-[40px] overflow-hidden shadow-xl border border-base-content/5">
                        <img src="https://images.unsplash.com/photo-1556910103-1c02745aae4d?q=80&w=800" alt="Chef Grace Ayesu preparing food" class="w-full h-full object-cover" loading="lazy">
                    </div>
                    <div class="absolute -bottom-8 -left-4 sm:-left-8 bg-primary text-white p-6 sm:p-8 rounded-[32px] shadow-xl">
                        <div class="text-4xl font-bold mb-1">10+</div>
                        <div class="text-[10px] font-bold uppercase tracking-[0.2em] text-white/70">Years of Culinary Excellence</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- 3. Mission & Vision --}}
    <section class="py-24 bg-base-200 border-b border-base-content/10">
        <div class="container mx-auto px-4 lg:px-8 max-w-4xl">
            <div class="text-center mb-16">
                <span class="text-[11px] font-bold text-accent uppercase tracking-[0.2em] mb-3 block">Our Purpose</span>
                <h2 class="text-4xl lg:text-5xl font-semibold text-base-content tracking-tight">What Drives Us</h2>
            </div>

            <div class="grid md:grid-cols-2 gap-8">
                <div class="bg-base-100 p-10 rounded-[32px] border border-base-content/10 shadow-sm">
                    <div class="size-12 bg-primary/10 text-primary rounded-2xl flex items-center justify-center mb-6">
                        <svg xmlns="http://www.w3.org/2000/svg" class="size-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" /></svg>
                    </div>
                    <h3 class="text-xl font-bold text-base-content mb-3">Our Mission</h3>
                    <p class="text-[15px] text-base-content/60 font-medium leading-relaxed">
                        To deliver exceptional catering experiences that celebrate Ghanaian culinary heritage while meeting the highest standards of quality, hygiene, and service.
                    </p>
                </div>

                <div class="bg-base-100 p-10 rounded-[32px] border border-base-content/10 shadow-sm">
                    <div class="size-12 bg-accent/10 text-accent rounded-2xl flex items-center justify-center mb-6">
                        <svg xmlns="http://www.w3.org/2000/svg" class="size-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                    </div>
                    <h3 class="text-xl font-bold text-base-content mb-3">Our Vision</h3>
                    <p class="text-[15px] text-base-content/60 font-medium leading-relaxed">
                        To be Ghana's most trusted and sought-after catering partner for every milestone celebration — from naming ceremonies to corporate galas.
                    </p>
                </div>
            </div>
        </div>
    </section>

    {{-- 4. What Sets Us Apart --}}
    <section class="py-24 bg-base-100 border-b border-base-content/10">
        <div class="container mx-auto px-4 lg:px-8">
            <div class="text-center mb-16">
                <span class="text-[11px] font-bold text-success uppercase tracking-[0.2em] mb-3 block">Why Choose Us</span>
                <h2 class="text-4xl lg:text-5xl font-semibold text-base-content tracking-tight">What Sets Us Apart</h2>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
                <div class="bg-base-200 p-8 rounded-[32px] border border-base-content/5 hover:shadow-lg transition-shadow group">
                    <div class="size-12 bg-white rounded-xl shadow-sm flex items-center justify-center text-success mb-6 group-hover:scale-110 transition-transform">
                        <svg xmlns="http://www.w3.org/2000/svg" class="size-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 10l-2 1m0 0l-2-1m2 1v2.5M20 7l-2 1m2-1l-2-1m2 1v2.5M14 4l-2-1-2 1M4 7l2-1M4 7l2 1M4 7v2.5M12 21l-2-1m2 1l2-1m-2 1v-2.5M6 18l-2-1v-2.5M18 18l2-1v-2.5" /></svg>
                    </div>
                    <h3 class="text-lg font-bold text-base-content mb-2">Fresh Local Ingredients</h3>
                    <p class="text-[13px] text-base-content/60 font-medium leading-relaxed">Sourced daily from local markets to ensure the richest, most authentic Ghanaian taste profiles.</p>
                </div>

                <div class="bg-base-200 p-8 rounded-[32px] border border-base-content/5 hover:shadow-lg transition-shadow group">
                    <div class="size-12 bg-white rounded-xl shadow-sm flex items-center justify-center text-primary mb-6 group-hover:scale-110 transition-transform">
                        <svg xmlns="http://www.w3.org/2000/svg" class="size-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    </div>
                    <h3 class="text-lg font-bold text-base-content mb-2">On-Time Delivery</h3>
                    <p class="text-[13px] text-base-content/60 font-medium leading-relaxed">Our logistics team ensures your food arrives hot, fresh, and exactly when your guests expect it.</p>
                </div>

                <div class="bg-base-200 p-8 rounded-[32px] border border-base-content/5 hover:shadow-lg transition-shadow group">
                    <div class="size-12 bg-white rounded-xl shadow-sm flex items-center justify-center text-accent mb-6 group-hover:scale-110 transition-transform">
                        <svg xmlns="http://www.w3.org/2000/svg" class="size-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" /></svg>
                    </div>
                    <h3 class="text-lg font-bold text-base-content mb-2">Expert Culinary Team</h3>
                    <p class="text-[13px] text-base-content/60 font-medium leading-relaxed">Led by executive chefs with decades of experience in high-volume catering and fine dining.</p>
                </div>

                <div class="bg-base-200 p-8 rounded-[32px] border border-base-content/5 hover:shadow-lg transition-shadow group">
                    <div class="size-12 bg-white rounded-xl shadow-sm flex items-center justify-center text-[#18542A] mb-6 group-hover:scale-110 transition-transform">
                        <svg xmlns="http://www.w3.org/2000/svg" class="size-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" /></svg>
                    </div>
                    <h3 class="text-lg font-bold text-base-content mb-2">FDA Certified</h3>
                    <p class="text-[13px] text-base-content/60 font-medium leading-relaxed">Fully approved and adhering to the highest standards of food safety and hygiene in Ghana.</p>
                </div>
            </div>
        </div>
    </section>

    {{-- 5. Stats Strip --}}
    <section class="py-16 lg:py-20 bg-primary relative overflow-hidden">
        <div class="absolute top-0 right-0 -mr-20 -mt-20 size-[30rem] bg-white/5 rounded-full blur-3xl"></div>
        <div class="absolute bottom-0 left-0 -ml-20 -mb-20 size-[20rem] bg-black/10 rounded-full blur-2xl"></div>

        <div class="container mx-auto px-4 lg:px-8 relative z-10">
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-8 text-center">
                <div>
                    <div class="text-4xl lg:text-5xl font-bold text-white mb-2">500+</div>
                    <div class="text-[10px] font-bold text-white/60 uppercase tracking-[0.2em]">Events Catered</div>
                </div>
                <div>
                    <div class="text-4xl lg:text-5xl font-bold text-white mb-2">10+</div>
                    <div class="text-[10px] font-bold text-white/60 uppercase tracking-[0.2em]">Years Experience</div>
                </div>
                <div>
                    <div class="text-4xl lg:text-5xl font-bold text-white mb-2">98%</div>
                    <div class="text-[10px] font-bold text-white/60 uppercase tracking-[0.2em]">Client Satisfaction</div>
                </div>
                <div>
                    <div class="flex justify-center mb-2">
                        <svg class="size-10 lg:size-12 text-white" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                    </div>
                    <div class="text-[10px] font-bold text-white/60 uppercase tracking-[0.2em]">FDA Approved</div>
                </div>
            </div>
        </div>
    </section>

    {{-- 6. CTA --}}
    <section class="py-24 lg:py-32 bg-base-200">
        <div class="container mx-auto px-4 lg:px-8 text-center max-w-3xl">
            <h2 class="text-4xl lg:text-5xl font-semibold text-base-content tracking-tight mb-6">Ready to Taste the Difference?</h2>
            <p class="text-[16px] text-base-content/60 font-medium mb-12 leading-relaxed">
                Let us make your next event unforgettable. Browse our packages or chat with us directly to get started.
            </p>

            <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
                <a href="{{ route('packages.browse') }}" class="w-full sm:w-auto bg-primary text-white text-[15px] font-bold px-10 py-5 rounded-xl hover:bg-primary-hover hover:scale-105 transition-all shadow-md">
                    View Our Packages
                </a>
                <a href="https://wa.me/{{ $whatsappNumber }}" target="_blank" class="w-full sm:w-auto bg-[#25D366] text-white text-[15px] font-bold px-10 py-5 rounded-xl hover:bg-[#20bd5a] hover:scale-105 transition-all shadow-md flex items-center justify-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="size-5" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M12.031 6.172c-3.181 0-5.767 2.586-5.768 5.766-.001 1.298.38 2.27 1.019 3.287l-.582 2.128 2.182-.573c.978.58 1.911.928 3.145.929 3.178 0 5.767-2.587 5.768-5.766.001-3.187-2.575-5.77-5.764-5.771zm3.392 8.244c-.144.405-.837.774-1.17.824-.299.045-.677.063-1.092-.069-.252-.08-.573-.187-.988-.365-1.739-.751-2.874-2.502-2.961-2.617-.087-.116-.708-.94-.708-1.793s.448-1.273.607-1.446c.159-.173.346-.217.462-.217l.332.006c.106.005.249-.04.39.298.144.347.491 1.2.534 1.287.043.087.072.188.014.304-.058.116-.087.188-.173.289l-.26.304c-.087.086-.177.18-.076.354.101.174.449.741.964 1.201.662.591 1.221.774 1.394.86s.274.072.376-.043c.101-.116.433-.506.549-.68.116-.173.231-.145.39-.087s1.011.477 1.184.564.289.13.332.202c.045.072.045.419-.1.824zm-3.423-14.416c-6.627 0-12 5.373-12 12s5.373 12 12 12 12-5.373 12-12-5.373-12-12-12zm.082 21.183c-1.653 0-3.331-.482-4.717-1.3l-5.365 1.488 1.474-5.26c-.822-1.391-1.309-3.093-1.309-4.821 0-5.319 4.316-9.635 9.636-9.635 5.316 0 9.632 4.316 9.632 9.635 0 5.317-4.316 9.631-9.351 9.631z"/>
                    </svg>
                    Chat on WhatsApp
                </a>
            </div>
        </div>
    </section>

</x-guest-layout>
