<x-guest-layout title="Terms of Service">
    @php
        $whatsappNumber = \App\Models\Setting::where('key', 'business_whatsapp')->value('value') ?: '233244203181';
    @endphp

    {{-- Hero Header --}}
    <section class="bg-base-200 pt-20 lg:pt-32 pb-12 lg:pb-16 border-b border-base-content/10">
        <div class="container mx-auto px-4 lg:px-8 max-w-4xl">
            <div class="inline-flex items-center gap-2 bg-base-content/5 text-base-content/50 text-[11px] font-bold px-4 py-2 rounded-full uppercase tracking-[0.2em] mb-6">
                Legal
            </div>
            <h1 class="text-4xl lg:text-5xl font-semibold text-base-content tracking-tight mb-4">Terms of Service</h1>
            <p class="text-[13px] text-base-content/40 font-medium mb-6">Last updated: March 12, 2026</p>
            <p class="text-[15px] text-base-content/60 font-medium leading-relaxed max-w-2xl">
                By using the Diamonds & Pearls Catering website and services, you agree to the following terms. Please read them carefully before placing a booking.
            </p>
        </div>
    </section>

    {{-- Table of Contents --}}
    <section class="bg-base-100 py-10 border-b border-base-content/10">
        <div class="container mx-auto px-4 lg:px-8 max-w-4xl">
            <div class="text-[11px] font-bold uppercase tracking-[0.2em] text-base-content/40 mb-5">In This Document</div>
            <ol class="grid sm:grid-cols-2 lg:grid-cols-3 gap-3">
                <li><a href="#section-1" class="flex items-center gap-3 text-[14px] text-base-content/70 font-semibold hover:text-primary transition-colors group">
                    <span class="size-7 bg-primary/5 rounded-lg flex items-center justify-center text-primary text-[12px] font-bold shrink-0 group-hover:bg-primary/10 transition-colors">1</span>
                    Agreement to Terms
                </a></li>
                <li><a href="#section-2" class="flex items-center gap-3 text-[14px] text-base-content/70 font-semibold hover:text-primary transition-colors group">
                    <span class="size-7 bg-primary/5 rounded-lg flex items-center justify-center text-primary text-[12px] font-bold shrink-0 group-hover:bg-primary/10 transition-colors">2</span>
                    Booking & Reservations
                </a></li>
                <li><a href="#section-3" class="flex items-center gap-3 text-[14px] text-base-content/70 font-semibold hover:text-primary transition-colors group">
                    <span class="size-7 bg-primary/5 rounded-lg flex items-center justify-center text-primary text-[12px] font-bold shrink-0 group-hover:bg-primary/10 transition-colors">3</span>
                    Cancellation Policy
                </a></li>
                <li><a href="#section-4" class="flex items-center gap-3 text-[14px] text-base-content/70 font-semibold hover:text-primary transition-colors group">
                    <span class="size-7 bg-primary/5 rounded-lg flex items-center justify-center text-primary text-[12px] font-bold shrink-0 group-hover:bg-primary/10 transition-colors">4</span>
                    Service Delivery
                </a></li>
                <li><a href="#section-5" class="flex items-center gap-3 text-[14px] text-base-content/70 font-semibold hover:text-primary transition-colors group">
                    <span class="size-7 bg-primary/5 rounded-lg flex items-center justify-center text-primary text-[12px] font-bold shrink-0 group-hover:bg-primary/10 transition-colors">5</span>
                    Governing Law
                </a></li>
            </ol>
        </div>
    </section>

    {{-- Content Sections --}}
    <section class="bg-base-200">
        <div class="container mx-auto px-4 lg:px-8 max-w-4xl">

            {{-- Section 1 --}}
            <div id="section-1" class="scroll-mt-24 py-10 lg:py-12 border-b border-base-content/10">
                <div class="flex items-center gap-3 mb-4">
                    <div class="size-8 bg-primary/10 rounded-lg flex items-center justify-center text-primary text-[13px] font-bold shrink-0">1</div>
                    <h2 class="text-xl font-bold text-base-content">Agreement to Terms</h2>
                </div>
                <p class="text-[15px] text-base-content/60 font-medium leading-relaxed pl-11">
                    By accessing and using the Diamonds & Pearls Catering website and services, you agree to be bound by these Terms of Service. If you do not agree to these terms, please refrain from using our platform.
                </p>
            </div>

            {{-- Section 2 --}}
            <div id="section-2" class="scroll-mt-24 py-10 lg:py-12 border-b border-base-content/10">
                <div class="flex items-center gap-3 mb-4">
                    <div class="size-8 bg-primary/10 rounded-lg flex items-center justify-center text-primary text-[13px] font-bold shrink-0">2</div>
                    <h2 class="text-xl font-bold text-base-content">Booking & Reservations</h2>
                </div>
                <p class="text-[15px] text-base-content/60 font-medium leading-relaxed pl-11">
                    A booking is only considered confirmed once the required deposit has been settled and verified by our registry team. We reserve the right to cancel any unverified bookings after 48 hours of initial placement.
                </p>
            </div>

            {{-- Section 3 --}}
            <div id="section-3" class="scroll-mt-24 py-10 lg:py-12 border-b border-base-content/10">
                <div class="flex items-center gap-3 mb-4">
                    <div class="size-8 bg-primary/10 rounded-lg flex items-center justify-center text-primary text-[13px] font-bold shrink-0">3</div>
                    <h2 class="text-xl font-bold text-base-content">Cancellation Policy</h2>
                </div>
                <ul class="list-disc pl-16 space-y-3 text-[15px] text-base-content/60 font-medium leading-relaxed">
                    <li>Cancellations made <strong class="text-base-content">14 days or more</strong> before the event qualify for a full refund of the deposit.</li>
                    <li>Cancellations made between <strong class="text-base-content">7 and 13 days</strong> before the event qualify for a 50% refund.</li>
                    <li>Cancellations made <strong class="text-base-content">less than 7 days</strong> before the event are non-refundable.</li>
                </ul>
            </div>

            {{-- Section 4 --}}
            <div id="section-4" class="scroll-mt-24 py-10 lg:py-12 border-b border-base-content/10">
                <div class="flex items-center gap-3 mb-4">
                    <div class="size-8 bg-primary/10 rounded-lg flex items-center justify-center text-primary text-[13px] font-bold shrink-0">4</div>
                    <h2 class="text-xl font-bold text-base-content">Service Delivery</h2>
                </div>
                <p class="text-[15px] text-base-content/60 font-medium leading-relaxed pl-11">
                    Service delivery is subject to the details provided during the booking process. Any structural changes to the event (location, timing, or guest count) must be communicated and approved at least 72 hours prior to the event.
                </p>
            </div>

            {{-- Section 5 --}}
            <div id="section-5" class="scroll-mt-24 py-10 lg:py-12">
                <div class="flex items-center gap-3 mb-4">
                    <div class="size-8 bg-primary/10 rounded-lg flex items-center justify-center text-primary text-[13px] font-bold shrink-0">5</div>
                    <h2 class="text-xl font-bold text-base-content">Governing Law</h2>
                </div>
                <p class="text-[15px] text-base-content/60 font-medium leading-relaxed pl-11">
                    These terms and conditions are governed by and construed in accordance with the laws of Ghana, and you irrevocably submit to the exclusive jurisdiction of the courts in that location.
                </p>
            </div>

        </div>
    </section>

    {{-- Bottom CTA --}}
    <section class="bg-base-100 py-16 lg:py-20 border-t border-base-content/10">
        <div class="container mx-auto px-4 lg:px-8 max-w-4xl text-center">
            <p class="text-[15px] text-base-content/60 font-medium mb-6">Have questions about our terms?</p>
            <div class="flex flex-wrap items-center justify-center gap-4">
                <a href="{{ route('contact') }}" class="text-[13px] font-bold px-6 py-3 rounded-xl bg-base-200 border border-base-content/10 text-base-content hover:bg-base-300 transition-colors">
                    Contact Us
                </a>
                <a href="https://wa.me/{{ $whatsappNumber }}" target="_blank" class="text-[13px] font-bold px-6 py-3 rounded-xl bg-[#25D366] text-white hover:bg-[#20bd5a] transition-colors flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="size-4" viewBox="0 0 24 24" fill="currentColor"><path d="M12.031 6.172c-3.181 0-5.767 2.586-5.768 5.766-.001 1.298.38 2.27 1.019 3.287l-.582 2.128 2.182-.573c.978.58 1.911.928 3.145.929 3.178 0 5.767-2.587 5.768-5.766.001-3.187-2.575-5.77-5.764-5.771zm3.392 8.244c-.144.405-.837.774-1.17.824-.299.045-.677.063-1.092-.069-.252-.08-.573-.187-.988-.365-1.739-.751-2.874-2.502-2.961-2.617-.087-.116-.708-.94-.708-1.793s.448-1.273.607-1.446c.159-.173.346-.217.462-.217l.332.006c.106.005.249-.04.39.298.144.347.491 1.2.534 1.287.043.087.072.188.014.304-.058.116-.087.188-.173.289l-.26.304c-.087.086-.177.18-.076.354.101.174.449.741.964 1.201.662.591 1.221.774 1.394.86s.274.072.376-.043c.101-.116.433-.506.549-.68.116-.173.231-.145.39-.087s1.011.477 1.184.564.289.13.332.202c.045.072.045.419-.1.824zm-3.423-14.416c-6.627 0-12 5.373-12 12s5.373 12 12 12 12-5.373 12-12-5.373-12-12-12zm.082 21.183c-1.653 0-3.331-.482-4.717-1.3l-5.365 1.488 1.474-5.26c-.822-1.391-1.309-3.093-1.309-4.821 0-5.319 4.316-9.635 9.636-9.635 5.316 0 9.632 4.316 9.632 9.635 0 5.317-4.316 9.631-9.351 9.631z"/></svg>
                    WhatsApp
                </a>
            </div>
        </div>
    </section>
</x-guest-layout>
