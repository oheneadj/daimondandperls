@php
    $whatsappNumber = dpc_setting('business_whatsapp', '233244203181');
@endphp

<section class="py-16 sm:py-24 bg-[#18542A] relative overflow-hidden">
    {{-- Blobs --}}
    <div class="absolute top-0 right-0 size-[500px] bg-white/5 blur-[100px] rounded-full -translate-y-1/3 translate-x-1/4"
        aria-hidden="true"></div>
    <div class="absolute bottom-0 left-0 size-[350px] bg-black/15 blur-[80px] rounded-full translate-y-1/3 -translate-x-1/4"
        aria-hidden="true"></div>
    <div class="absolute top-1/2 left-1/2 size-[600px] bg-white/3 blur-[120px] rounded-full -translate-x-1/2 -translate-y-1/2"
        aria-hidden="true"></div>
    {{-- Floating decorative icons --}}
    <div class="absolute top-8 left-8 text-white/8 hidden lg:block" aria-hidden="true">
        <svg xmlns="http://www.w3.org/2000/svg" class="size-20" fill="none" viewBox="0 0 24 24" stroke="currentColor"
            stroke-width="1">
            <path stroke-linecap="round" stroke-linejoin="round"
                d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>
    </div>
    <div class="absolute bottom-12 right-10 text-white/6 hidden lg:block rotate-12" aria-hidden="true">
        <svg xmlns="http://www.w3.org/2000/svg" class="size-28" fill="none" viewBox="0 0 24 24" stroke="currentColor"
            stroke-width="0.8">
            <path stroke-linecap="round" stroke-linejoin="round"
                d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
        </svg>
    </div>
    <div class="absolute top-1/3 right-4 text-white/5 hidden xl:block -rotate-6" aria-hidden="true">
        <svg xmlns="http://www.w3.org/2000/svg" class="size-24" fill="none" viewBox="0 0 24 24" stroke="currentColor"
            stroke-width="0.8">
            <path stroke-linecap="round" stroke-linejoin="round"
                d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4" />
        </svg>
    </div>

    <div class="container mx-auto px-4 lg:px-8 max-w-4xl relative z-10">
        <div class="text-center mb-10 sm:mb-16">
            <span class="text-[11px] font-bold text-white/60 uppercase tracking-[0.2em] mb-3 block">Got
                Questions?</span>
            <h2 class="text-3xl sm:text-4xl lg:text-5xl font-semibold text-white tracking-tight">Frequently Asked
                Questions</h2>
        </div>

        <div class="space-y-3" x-data="{ active: null }">
            @php
                $faqs = [
                    ['q' => 'How far in advance should I book?', 'a' => 'For events, we recommend booking at least one month in advance to ensure availability and proper planning.'],
                    ['q' => 'Do you cater outside Accra?', 'a' => 'Yes! We are willing to deliver anywhere to meet our clients\' needs, provided transportation arrangements and costs are covered.'],
                    ['q' => 'What is your minimum guest count?', 'a' => 'Our minimum order size is 40 plates for buffet services or 20 packs for our packed meal options.'],
                    ['q' => 'Is a deposit required to confirm my booking?', 'a' => 'Yes. A booking is only considered officially confirmed once the required deposit has been settled and verified by our team.'],
                    ['q' => 'Can I customise the menu?', 'a' => 'Absolutely. We offer full customization to suit your specific needs. We are also flexible with changes, such as switching from packed meals to buffet service if your needs evolve.'],
                    ['q' => 'What is your cancellation and refund policy?', 'a' => 'Cancellations made at least 7 days prior to the event are eligible for a 90% refund. Cancellations within the 7-day window receive a 70% refund, as preparations and costs are already being incurred.'],
                    ['q' => 'Do you take photos or recordings during events?', 'a' => 'Yes. As part of our <a href="' . route('terms') . '" class="underline hover:text-white transition-colors">terms of service</a>, we reserve the right to photograph or record events for promotional and internal use.'],
                ];
            @endphp

            @foreach($faqs as $index => $faq)
                <div class="bg-white/10 backdrop-blur-sm rounded-2xl overflow-hidden transition-all duration-300">
                    <button
                        class="w-full px-6 py-5 text-left flex justify-between items-center hover:bg-white/5 focus:outline-none"
                        @click="active === {{ $index }} ? active = null : active = {{ $index }}">
                        <span class="font-bold text-white text-[15px] pr-8">{{ $faq['q'] }}</span>
                        <svg class="size-5 text-white/60 transform transition-transform duration-300 shrink-0"
                            :class="{'rotate-180': active === {{ $index }}}" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>
                    <div class="px-6 py-5 bg-black/10" x-show="active === {{ $index }}" x-collapse x-cloak
                        style="display: none;">
                        <p class="text-[14px] text-white/70 font-medium leading-relaxed">{!! $faq['a'] !!}</p>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="text-center mt-12">
            <p class="text-[14px] font-medium text-white/60">
                Still have questions? <a href="https://wa.me/{{ $whatsappNumber }}"
                    class="text-white font-bold hover:underline">Chat with us on WhatsApp</a>
            </p>
        </div>
    </div>
</section>