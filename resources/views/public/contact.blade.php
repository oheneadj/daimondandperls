<x-guest-layout title="Contact Us">
    @php
        $whatsappNumber = \App\Models\Setting::where('key', 'business_whatsapp')->value('value') ?: '233244203181';
    @endphp

    {{-- Hero --}}
    <x-ui.page-hero
        badge="Get in Touch"
        title="Let's Plan Something<br class='hidden sm:block'> Extraordinary"
        subtitle="We would be honoured to discuss your upcoming event and tailor a catering experience that exceeds your expectations."
    />

    {{-- Main Content --}}
    <section class="py-20 lg:py-28 bg-base-100">
        <div class="container mx-auto px-4 lg:px-8">
            <div class="max-w-5xl mx-auto grid lg:grid-cols-12 gap-12 lg:gap-16 items-start">

                {{-- Contact Info --}}
                <div class="lg:col-span-5 space-y-6">

                    {{-- Contact details --}}
                    <div class="bg-base-200 rounded-2xl p-8 space-y-6">
                        <h2 class="text-[13px] font-bold text-base-content/40 uppercase tracking-widest">Contact Details</h2>

                        <a href="https://maps.google.com/?q=Accra+Ghana" target="_blank" class="flex items-start gap-4 group">
                            <div class="size-11 bg-primary/10 rounded-xl flex items-center justify-center shrink-0">
                                <svg xmlns="http://www.w3.org/2000/svg" class="size-5 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                            </div>
                            <div>
                                <p class="text-[12px] font-bold text-base-content/40 uppercase tracking-widest mb-0.5">Address</p>
                                <p class="text-[14px] font-semibold text-base-content group-hover:text-primary transition-colors leading-snug">P.O. Box 18123<br>Accra, Ghana</p>
                            </div>
                        </a>

                        <a href="tel:+233244203181" class="flex items-start gap-4 group">
                            <div class="size-11 bg-[#18542A]/10 rounded-xl flex items-center justify-center shrink-0">
                                <svg xmlns="http://www.w3.org/2000/svg" class="size-5 text-[#18542A]" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" /></svg>
                            </div>
                            <div>
                                <p class="text-[12px] font-bold text-base-content/40 uppercase tracking-widest mb-0.5">Phone</p>
                                <p class="text-[14px] font-semibold text-base-content group-hover:text-[#18542A] transition-colors">+233 244 203 181</p>
                            </div>
                        </a>

                        <a href="mailto:graceayesu@yahoo.com" class="flex items-start gap-4 group">
                            <div class="size-11 bg-accent/15 rounded-xl flex items-center justify-center shrink-0">
                                <svg xmlns="http://www.w3.org/2000/svg" class="size-5 text-amber-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" /></svg>
                            </div>
                            <div>
                                <p class="text-[12px] font-bold text-base-content/40 uppercase tracking-widest mb-0.5">Email</p>
                                <p class="text-[14px] font-semibold text-base-content group-hover:text-amber-600 transition-colors">graceayesu@yahoo.com</p>
                            </div>
                        </a>
                    </div>

                    {{-- Inquiry Hours --}}
                    <div class="bg-[#18542A] p-8 rounded-2xl text-white space-y-4 shadow-lg relative overflow-hidden">
                        <div class="absolute top-0 right-0 size-32 bg-white/5 rounded-full -translate-y-1/2 translate-x-1/2" aria-hidden="true"></div>
                        <h3 class="text-[13px] font-bold uppercase tracking-widest text-white/60">Inquiry Hours</h3>
                        <div class="space-y-3 text-[14px] font-medium relative z-10">
                            <div class="flex justify-between items-center">
                                <span class="text-white/70">Mon — Fri</span>
                                <span class="font-bold text-white bg-white/10 px-3 py-1 rounded-lg text-[13px]">8:00 AM — 6:00 PM</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-white/70">Saturday</span>
                                <span class="font-bold text-white bg-white/10 px-3 py-1 rounded-lg text-[13px]">9:00 AM — 4:00 PM</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-white/70">Sunday</span>
                                <span class="text-white/40 text-[13px]">Closed</span>
                            </div>
                        </div>
                        <div class="pt-3 border-t border-white/10">
                            <p class="text-[12px] text-white/50 font-medium">Usually replies within 24 hours</p>
                        </div>
                    </div>

                    {{-- WhatsApp --}}
                    <a href="https://wa.me/{{ $whatsappNumber }}" target="_blank"
                       class="flex items-center gap-4 p-5 bg-[#25D366]/10 rounded-2xl hover:bg-[#25D366]/20 transition-all group">
                        <div class="size-12 bg-[#25D366] rounded-xl flex items-center justify-center shrink-0 shadow-md">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512" fill="currentColor" class="size-6 text-white">
                                <path d="M380.9 97.1C339 55.1 283.2 32 223.9 32c-122.4 0-222 99.6-222 222 0 39.1 10.2 77.3 29.6 111L0 480l117.7-30.9c32.4 17.7 68.9 27 106.1 27h.1c122.3 0 224.1-99.6 224.1-222 0-59.3-25.2-115-67.1-157zm-157 341.6c-33.2 0-65.7-8.9-94-25.7l-6.7-4-69.8 18.3L72 359.2l-4.4-7c-18.5-29.4-28.2-63.3-28.2-98.2 0-101.7 82.8-184.5 184.6-184.5 49.3 0 95.6 19.2 130.4 54.1 34.8 34.9 56.2 81.2 56.1 130.5 0 101.8-84.9 184.6-186.6 184.6zm101.2-138.2c-5.5-2.8-32.8-16.2-37.9-18-5.1-1.9-8.8-2.8-12.5 2.8-3.7 5.6-14.3 18-17.6 21.8-3.2 3.7-6.5 4.2-12 1.4-32.6-16.3-54-29.1-75.5-66-5.7-9.8 5.7-9.1 16.3-30.3 1.8-3.7.9-6.9-.5-9.7-1.4-2.8-12.5-30.1-17.1-41.2-4.5-10.8-9.1-9.3-12.5-9.5-3.2-.2-6.9-.2-10.6-.2-3.7 0-9.7 1.4-14.8 6.9-5.1 5.6-19.4 19-19.4 46.3 0 27.3 19.9 53.7 22.6 57.4 2.8 3.7 39.1 59.7 94.8 83.8 35.2 15.2 49 16.5 66.6 13.9 10.7-1.6 32.8-13.4 37.4-26.4 4.6-13 4.6-24.1 3.2-26.4-1.3-2.5-5-3.9-10.5-6.6z"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-[14px] font-bold text-base-content group-hover:text-[#25D366] transition-colors">Chat on WhatsApp</p>
                            <p class="text-[12px] text-base-content/50 font-medium">Instant replies during inquiry hours</p>
                        </div>
                        <svg class="size-4 text-base-content/20 ml-auto group-hover:text-[#25D366] transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                    </a>
                </div>

                {{-- Contact Form --}}
                <div class="lg:col-span-7 bg-base-100 p-8 lg:p-10 rounded-2xl shadow-xl ring-1 ring-base-content/5">
                    <div class="mb-8">
                        <div class="inline-flex items-center gap-2 bg-primary/10 text-primary text-[11px] font-bold px-3 py-1.5 rounded-full uppercase tracking-widest mb-4">
                            <span class="size-1.5 rounded-full bg-primary animate-pulse"></span>
                            Send a Message
                        </div>
                        <h2 class="text-2xl font-bold text-base-content mb-1">How Can We Help?</h2>
                        <p class="text-[14px] text-base-content/50 font-medium">Fill in the details below and we'll be in touch shortly.</p>
                    </div>
                    <livewire:contact-form />
                </div>

            </div>
        </div>
    </section>

    {{-- FAQ --}}
    <section class="py-20 lg:py-24 bg-[#18542A] relative overflow-hidden">
        <div class="absolute top-0 right-0 size-[500px] bg-white/5 blur-[100px] rounded-full -translate-y-1/3 translate-x-1/3" aria-hidden="true"></div>
        <div class="absolute bottom-0 left-0 size-[400px] bg-black/15 blur-[80px] rounded-full translate-y-1/3 -translate-x-1/4" aria-hidden="true"></div>
        <div class="absolute top-1/2 left-1/2 size-[600px] bg-white/3 blur-[120px] rounded-full -translate-x-1/2 -translate-y-1/2" aria-hidden="true"></div>
        {{-- Floating icons --}}
        <div class="absolute top-10 left-10 text-white/8 hidden lg:block -rotate-12" aria-hidden="true">
            <svg xmlns="http://www.w3.org/2000/svg" class="size-24" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="0.8"><path stroke-linecap="round" stroke-linejoin="round" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        </div>
        <div class="absolute bottom-10 right-10 text-white/6 hidden lg:block rotate-6" aria-hidden="true">
            <svg xmlns="http://www.w3.org/2000/svg" class="size-28" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="0.8"><path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
        </div>
        <div class="absolute top-1/3 right-8 text-white/5 hidden xl:block" aria-hidden="true">
            <svg xmlns="http://www.w3.org/2000/svg" class="size-20" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="0.9"><path stroke-linecap="round" stroke-linejoin="round" d="M17 8h2a2 2 0 012 2v6a2 2 0 01-2 2h-2v4l-4-4H9a1.994 1.994 0 01-1.414-.586m0 0L11 14h4a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2v4l.586-.586z"/></svg>
        </div>

        <div class="container mx-auto px-4 lg:px-8 max-w-3xl relative z-10">
            <div class="text-center mb-12">
                <span class="inline-block text-[11px] font-bold text-white/60 uppercase tracking-widest mb-3 bg-white/10 px-3 py-1.5 rounded-full">FAQ</span>
                <h2 class="text-3xl lg:text-4xl font-semibold text-white tracking-tight mt-4">Frequently Asked Questions</h2>
            </div>

            <div class="space-y-3">
                @foreach([
                    ['q' => 'How far in advance should I book?', 'a' => 'We recommend booking at least 2–4 weeks in advance for meal orders and 4–8 weeks for full event catering. Popular dates (especially weekends) fill up quickly, so earlier is always better.'],
                    ['q' => 'Do you cater outside Accra?', 'a' => 'Yes, we cater throughout Greater Accra and select regions. Delivery fees and availability vary by location. Contact us to confirm coverage for your area.'],
                    ['q' => 'What is your minimum guest count for event catering?', 'a' => 'Our event catering packages typically start from 50 guests. For smaller gatherings, our meal packages may be a better fit — browse them on our packages page.'],
                    ['q' => 'Is a deposit required to confirm my booking?', 'a' => 'Yes. A deposit is required to secure your booking date. The deposit percentage is communicated during the booking process and is deducted from your final balance.'],
                    ['q' => 'Can I customise the menu?', 'a' => 'Absolutely. We offer full menu customisation for event bookings. Speak with our team about dietary requirements, preferred dishes, and portion sizes during your enquiry.'],
                    ['q' => 'What happens if I need to reschedule?', 'a' => 'Life happens — we understand. Please contact us as soon as possible. Rescheduling is subject to availability, and our cancellation and rescheduling policy applies as outlined in our Terms & Conditions.'],
                ] as $faq)
                    <div class="bg-white/10 backdrop-blur-sm rounded-2xl overflow-hidden" x-data="{ open: false }">
                        <button
                            class="w-full flex items-center justify-between gap-4 p-5 text-left"
                            @click="open = !open"
                            :aria-expanded="open"
                        >
                            <span class="text-[15px] font-semibold text-white">{{ $faq['q'] }}</span>
                            <span class="size-7 rounded-full bg-white/10 text-white flex items-center justify-center shrink-0 transition-transform duration-200" :class="open ? 'rotate-45' : ''">
                                <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
                            </span>
                        </button>
                        <div x-show="open" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 -translate-y-1" x-transition:enter-end="opacity-100 translate-y-0">
                            <p class="px-5 pb-5 text-[14px] text-white/70 font-medium leading-relaxed">{{ $faq['a'] }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- Bottom CTA --}}
    <section class="py-20 lg:py-24 bg-base-200">
        <div class="container mx-auto px-4 lg:px-8 text-center max-w-3xl">
            <h2 class="text-3xl lg:text-4xl font-semibold text-base-content tracking-tight mb-4">Ready to Book?</h2>
            <p class="text-[15px] text-base-content/60 font-medium mb-8 leading-relaxed">
                Skip the form and explore our packages directly — or start a booking and we'll follow up.
            </p>
            <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
                <x-ui.button href="{{ route('packages.browse') }}" variant="primary" size="lg">
                    Browse Our Menu
                </x-ui.button>
                <x-ui.button href="{{ route('event-booking') }}" variant="green" size="lg">
                    Plan an Event
                </x-ui.button>
            </div>
        </div>
    </section>

</x-guest-layout>
