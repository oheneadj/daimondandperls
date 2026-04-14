<x-guest-layout title="Contact Us">
    @php
        $whatsappNumber = \App\Models\Setting::where('key', 'business_whatsapp')->value('value') ?: '233244203181';
    @endphp

    {{-- Hero --}}
    <section class="relative bg-[#18542A] py-20 lg:py-32 overflow-hidden">
        <div class="absolute top-0 right-0 size-[500px] bg-white/5 blur-[120px] rounded-full -translate-y-1/2 translate-x-1/4" aria-hidden="true"></div>
        <div class="absolute bottom-0 left-0 size-[400px] bg-black/10 blur-[120px] rounded-full translate-y-1/2 -translate-x-1/4" aria-hidden="true"></div>

        <div class="container mx-auto px-4 lg:px-8 text-center relative z-10">
            <div class="inline-flex items-center gap-2 bg-white/10 text-white text-[11px] font-bold px-4 py-2 rounded-full uppercase tracking-widest mb-6">
                <span class="size-2 rounded-full bg-accent animate-pulse"></span>
                Get in Touch
            </div>
            <h1 class="text-5xl lg:text-6xl font-semibold text-white mb-6 tracking-tight">Let's Plan Something<br class="hidden sm:block"> Extraordinary</h1>
            <p class="text-lg text-white/70 font-medium max-w-2xl mx-auto leading-relaxed">
                We would be honoured to discuss your upcoming event and tailor a catering experience that exceeds your expectations.
            </p>
        </div>
    </section>

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
                            <svg class="size-6 text-white" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M12.031 6.172c-3.181 0-5.767 2.586-5.768 5.766-.001 1.298.38 2.27 1.019 3.287l-.582 2.128 2.182-.573c.978.58 1.911.928 3.145.929 3.178 0 5.767-2.587 5.768-5.766.001-3.187-2.575-5.77-5.764-5.771zm3.392 8.244c-.144.405-.837.774-1.17.824-.299.045-.677.063-1.092-.069-.252-.08-.573-.187-.988-.365-1.739-.751-2.874-2.502-2.961-2.617-.087-.116-.708-.94-.708-1.793s.448-1.273.607-1.446c.159-.173.346-.217.462-.217l.332.006c.106.005.249-.04.39.298.144.347.491 1.2.534 1.287.043.087.072.188.014.304-.058.116-.087.188-.173.289l-.26.304c-.087.086-.177.18-.076.354.101.174.449.741.964 1.201.662.591 1.221.774 1.394.86s.274.072.376-.043c.101-.116.433-.506.549-.68.116-.173.231-.145.39-.087s1.011.477 1.184.564.289.13.332.202c.045.072.045.419-.1.824zm-3.423-14.416c-6.627 0-12 5.373-12 12s5.373 12 12 12 12-5.373 12-12-5.373-12-12-12zm.082 21.183c-1.653 0-3.331-.482-4.717-1.3l-5.365 1.488 1.474-5.26c-.822-1.391-1.309-3.093-1.309-4.821 0-5.319 4.316-9.635 9.636-9.635 5.316 0 9.632 4.316 9.632 9.635 0 5.317-4.316 9.631-9.351 9.631z"/>
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
        <div class="absolute top-0 right-0 size-[400px] bg-white/5 blur-[80px] rounded-full -translate-y-1/2 translate-x-1/4" aria-hidden="true"></div>
        <div class="absolute bottom-0 left-0 size-[300px] bg-black/10 blur-[60px] rounded-full translate-y-1/2 -translate-x-1/4" aria-hidden="true"></div>

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
