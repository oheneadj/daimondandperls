@php
    $whatsappNumber = \App\Models\Setting::where('key', 'business_whatsapp')->value('value') ?: '233244203181';
@endphp

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
                Still have questions? <a href="https://wa.me/{{ $whatsappNumber }}" class="text-primary font-bold hover:underline">Chat with us on WhatsApp</a>
            </p>
        </div>
    </div>
</section>
