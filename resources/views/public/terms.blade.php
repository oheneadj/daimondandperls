<x-guest-layout title="Terms of Service">
    @php
        $whatsappNumber = dpc_setting('business_whatsapp', '233244203181');
    @endphp

    {{-- Hero Header --}}
    <section class="relative bg-primary pt-20 lg:pt-28 pb-14 lg:pb-20 overflow-hidden">
        <div class="absolute top-0 right-0 size-[600px] bg-white/5 blur-[120px] rounded-full -translate-y-1/2 translate-x-1/3" aria-hidden="true"></div>
        <div class="absolute bottom-0 left-0 size-[400px] bg-black/10 blur-[100px] rounded-full translate-y-1/2 -translate-x-1/4" aria-hidden="true"></div>
        <div class="absolute inset-0 bg-[url('data:image/svg+xml,%3Csvg width=\'60\' height=\'60\' viewBox=\'0 0 60 60\' xmlns=\'http://www.w3.org/2000/svg\'%3E%3Cg fill=\'none\' fill-rule=\'evenodd\'%3E%3Cg fill=\'%23ffffff\' fill-opacity=\'0.03\'%3E%3Cpath d=\'M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z\'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E')]" aria-hidden="true"></div>

        <div class="container mx-auto px-4 lg:px-8 max-w-4xl relative z-10">
            <div class="inline-flex items-center gap-2 bg-white/15 text-white text-[11px] font-bold px-4 py-2 rounded-full uppercase tracking-widest mb-8">
                <span class="size-2 rounded-full bg-accent animate-pulse"></span>
                Legal
            </div>
            <h1 class="text-4xl sm:text-5xl lg:text-6xl font-semibold text-white tracking-tight mb-3 leading-tight">Terms of Service</h1>
            <p class="text-[13px] text-white/50 font-medium mb-6">Last updated: April 14, 2026</p>
            <p class="text-[15px] text-white/70 font-medium leading-relaxed max-w-2xl">
                By using the Diamonds & Pearls Catering website and services, you agree to the following terms. Please read them carefully before placing a booking.
            </p>
        </div>
    </section>

    {{-- Table of Contents --}}
    <section class="bg-base-100 py-10 border-b border-base-content/10">
        <div class="container mx-auto px-4 lg:px-8 max-w-4xl">
            <div class="text-[11px] font-bold uppercase tracking-[0.2em] text-base-content/40 mb-5">In This Document</div>
            <ol class="grid sm:grid-cols-2 lg:grid-cols-3 gap-3">
                @foreach([
                    ['id' => 'section-1', 'label' => 'Agreement to Terms'],
                    ['id' => 'section-2', 'label' => 'Booking & Reservations'],
                    ['id' => 'section-3', 'label' => 'Cancellation Policy'],
                    ['id' => 'section-4', 'label' => 'Service Delivery'],
                    ['id' => 'section-5', 'label' => 'Food Safety & Liability'],
                    ['id' => 'section-6', 'label' => 'Force Majeure'],
                    ['id' => 'section-7', 'label' => 'Photo & Media Rights'],
                    ['id' => 'section-8', 'label' => 'Governing Law'],
                ] as $i => $section)
                    <li>
                        <a href="#{{ $section['id'] }}" class="flex items-center gap-3 text-[14px] text-base-content/70 font-semibold hover:text-primary transition-colors group">
                            <span class="size-7 bg-primary/5 rounded-lg flex items-center justify-center text-primary text-[12px] font-bold shrink-0 group-hover:bg-primary/10 transition-colors">{{ $i + 1 }}</span>
                            {{ $section['label'] }}
                        </a>
                    </li>
                @endforeach
            </ol>
        </div>
    </section>

    {{-- Content Sections (alternating bg) --}}
    <div id="section-1" class="scroll-mt-24 bg-base-200 border-b border-base-content/10">
        <div class="container mx-auto px-4 lg:px-8 max-w-4xl py-10 lg:py-12">
            <div class="flex items-center gap-3 mb-4">
                <div class="size-8 bg-primary/10 rounded-lg flex items-center justify-center text-primary text-[13px] font-bold shrink-0">1</div>
                <h2 class="text-xl font-bold text-base-content">Agreement to Terms</h2>
            </div>
            <p class="text-[15px] text-base-content/60 font-medium leading-relaxed pl-11">
                By accessing and using the Diamonds & Pearls Catering website and services, you agree to be bound by these Terms of Service. If you do not agree to these terms, please refrain from using our platform.
            </p>
        </div>
    </div>

    <div id="section-2" class="scroll-mt-24 bg-base-100 border-b border-base-content/10">
        <div class="container mx-auto px-4 lg:px-8 max-w-4xl py-10 lg:py-12">
            <div class="flex items-center gap-3 mb-4">
                <div class="size-8 bg-primary/10 rounded-lg flex items-center justify-center text-primary text-[13px] font-bold shrink-0">2</div>
                <h2 class="text-xl font-bold text-base-content">Booking & Reservations</h2>
            </div>
            <p class="text-[15px] text-base-content/60 font-medium leading-relaxed pl-11">
                A booking is only considered confirmed once the required deposit has been settled and verified by our team. The deposit amount will be clearly communicated at the time of booking. We reserve the right to cancel any unverified bookings after 48 hours of initial placement.
            </p>
        </div>
    </div>

    <div id="section-3" class="scroll-mt-24 bg-base-200 border-b border-base-content/10">
        <div class="container mx-auto px-4 lg:px-8 max-w-4xl py-10 lg:py-12">
            <div class="flex items-center gap-3 mb-4">
                <div class="size-8 bg-primary/10 rounded-lg flex items-center justify-center text-primary text-[13px] font-bold shrink-0">3</div>
                <h2 class="text-xl font-bold text-base-content">Cancellation Policy</h2>
            </div>
            <ul class="list-disc pl-16 space-y-3 text-[15px] text-base-content/60 font-medium leading-relaxed">
                <li>Cancellations made <strong class="text-base-content">14 days or more</strong> before the event qualify for a full refund of the deposit.</li>
                <li>Cancellations made between <strong class="text-base-content">7 and 13 days</strong> before the event qualify for a 50% refund.</li>
                <li>Cancellations made <strong class="text-base-content">less than 7 days</strong> before the event are non-refundable.</li>
                <li>Any changes to guest count must be communicated at least <strong class="text-base-content">72 hours</strong> before the event and may affect the final invoice.</li>
            </ul>
        </div>
    </div>

    <div id="section-4" class="scroll-mt-24 bg-base-100 border-b border-base-content/10">
        <div class="container mx-auto px-4 lg:px-8 max-w-4xl py-10 lg:py-12">
            <div class="flex items-center gap-3 mb-4">
                <div class="size-8 bg-primary/10 rounded-lg flex items-center justify-center text-primary text-[13px] font-bold shrink-0">4</div>
                <h2 class="text-xl font-bold text-base-content">Service Delivery</h2>
            </div>
            <p class="text-[15px] text-base-content/60 font-medium leading-relaxed pl-11">
                Service delivery is subject to the details provided during the booking process. Any structural changes to the event — including location, timing, or guest count — must be communicated and approved at least 72 hours prior to the event. Diamonds & Pearls Catering is not liable for delays or service adjustments resulting from inaccurate information provided by the customer.
            </p>
        </div>
    </div>

    <div id="section-5" class="scroll-mt-24 bg-base-200 border-b border-base-content/10">
        <div class="container mx-auto px-4 lg:px-8 max-w-4xl py-10 lg:py-12">
            <div class="flex items-center gap-3 mb-4">
                <div class="size-8 bg-primary/10 rounded-lg flex items-center justify-center text-primary text-[13px] font-bold shrink-0">5</div>
                <h2 class="text-xl font-bold text-base-content">Food Safety & Liability</h2>
            </div>
            <div class="pl-11 space-y-4 text-[15px] text-base-content/60 font-medium leading-relaxed">
                <p>
                    Diamonds & Pearls Catering is FDA certified and adheres to the highest standards of food preparation and hygiene. However, we cannot guarantee that our dishes are entirely free from allergens such as nuts, gluten, dairy, or shellfish, as our kitchen handles these ingredients.
                </p>
                <p>
                    Customers with known food allergies or dietary restrictions <strong class="text-base-content">must disclose this at the time of booking</strong>. We will make reasonable accommodations where possible, but cannot accept liability for allergic reactions resulting from undisclosed conditions.
                </p>
                <p>
                    Once food has been delivered and accepted by the customer or event coordinator, responsibility for safe handling, storage, and serving passes to the customer. We are not liable for any illness or injury arising from improper handling after delivery.
                </p>
            </div>
        </div>
    </div>

    <div id="section-6" class="scroll-mt-24 bg-base-100 border-b border-base-content/10">
        <div class="container mx-auto px-4 lg:px-8 max-w-4xl py-10 lg:py-12">
            <div class="flex items-center gap-3 mb-4">
                <div class="size-8 bg-primary/10 rounded-lg flex items-center justify-center text-primary text-[13px] font-bold shrink-0">6</div>
                <h2 class="text-xl font-bold text-base-content">Force Majeure</h2>
            </div>
            <p class="text-[15px] text-base-content/60 font-medium leading-relaxed pl-11">
                Diamonds & Pearls Catering shall not be held liable for failure to fulfil any booking in the event of circumstances beyond our reasonable control, including but not limited to natural disasters, government-imposed restrictions, severe weather, civil unrest, or supplier failures. In such cases, we will make every reasonable effort to reschedule your service or provide a partial or full refund, at our discretion.
            </p>
        </div>
    </div>

    <div id="section-7" class="scroll-mt-24 bg-base-200 border-b border-base-content/10">
        <div class="container mx-auto px-4 lg:px-8 max-w-4xl py-10 lg:py-12">
            <div class="flex items-center gap-3 mb-4">
                <div class="size-8 bg-primary/10 rounded-lg flex items-center justify-center text-primary text-[13px] font-bold shrink-0">7</div>
                <h2 class="text-xl font-bold text-base-content">Photo & Media Rights</h2>
            </div>
            <p class="text-[15px] text-base-content/60 font-medium leading-relaxed pl-11">
                Diamonds & Pearls Catering may photograph or record events we cater for use in our marketing materials, social media, and portfolio. No personal identifiers will be used without explicit consent. If you do not wish for your event to be photographed by our team, please notify us in writing at least 7 days before your event.
            </p>
        </div>
    </div>

    <div id="section-8" class="scroll-mt-24 bg-base-100">
        <div class="container mx-auto px-4 lg:px-8 max-w-4xl py-10 lg:py-12">
            <div class="flex items-center gap-3 mb-4">
                <div class="size-8 bg-primary/10 rounded-lg flex items-center justify-center text-primary text-[13px] font-bold shrink-0">8</div>
                <h2 class="text-xl font-bold text-base-content">Governing Law</h2>
            </div>
            <p class="text-[15px] text-base-content/60 font-medium leading-relaxed pl-11">
                These terms and conditions are governed by and construed in accordance with the laws of Ghana. You irrevocably submit to the exclusive jurisdiction of the courts in Ghana for the resolution of any disputes arising from these terms or your use of our services.
            </p>
        </div>
    </div>

    {{-- Bottom CTA --}}
    <section class="bg-[#18542A] py-16 lg:py-20 relative overflow-hidden">
        <div class="absolute top-0 right-0 size-[450px] bg-white/5 blur-[100px] rounded-full -translate-y-1/3 translate-x-1/3" aria-hidden="true"></div>
        <div class="absolute bottom-0 left-0 size-[350px] bg-black/15 blur-[80px] rounded-full translate-y-1/3 -translate-x-1/4" aria-hidden="true"></div>
        <div class="absolute top-1/2 left-1/2 size-[500px] bg-white/3 blur-[100px] rounded-full -translate-x-1/2 -translate-y-1/2" aria-hidden="true"></div>
        {{-- Floating icons --}}
        <div class="absolute top-4 left-8 text-white/8 hidden lg:block -rotate-12" aria-hidden="true">
            <svg xmlns="http://www.w3.org/2000/svg" class="size-20" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="0.9"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
        </div>
        <div class="absolute bottom-4 right-8 text-white/6 hidden lg:block rotate-6" aria-hidden="true">
            <svg xmlns="http://www.w3.org/2000/svg" class="size-24" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="0.8"><path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
        </div>
        <div class="container mx-auto px-4 lg:px-8 max-w-4xl text-center relative z-10">
            <p class="text-[11px] font-bold text-white/50 uppercase tracking-widest mb-3">Still have questions?</p>
            <h2 class="text-2xl lg:text-3xl font-semibold text-white tracking-tight mb-2">We're Happy to Help</h2>
            <p class="text-[14px] text-white/60 font-medium mb-8">Reach out via the contact form or chat with us directly on WhatsApp.</p>
            <div class="flex flex-wrap items-center justify-center gap-4">
                <x-ui.button href="{{ route('contact') }}" variant="black" size="md">
                    <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                    Send a Message
                </x-ui.button>
                <x-ui.whatsapp-button label="Chat on WhatsApp" />
            </div>
        </div>
    </section>
</x-guest-layout>
