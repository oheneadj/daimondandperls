<x-guest-layout title="Privacy Policy">
    @php
        $whatsappNumber = \App\Models\Setting::where('key', 'business_whatsapp')->value('value') ?: '233244203181';
    @endphp

    {{-- Hero Header --}}
    <section class="relative bg-base-200 pt-20 lg:pt-32 pb-12 lg:pb-16 overflow-hidden border-b border-base-content/10">
        <div class="absolute top-0 right-0 size-[500px] bg-primary/5 blur-[120px] rounded-full -translate-y-1/2 translate-x-1/4" aria-hidden="true"></div>
        <div class="absolute bottom-0 left-0 size-[400px] bg-accent/5 blur-[120px] rounded-full translate-y-1/2 -translate-x-1/4" aria-hidden="true"></div>

        <div class="container mx-auto px-4 lg:px-8 max-w-4xl relative z-10">
            <div class="inline-flex items-center gap-2 bg-primary/10 text-primary text-[11px] font-bold px-4 py-2 rounded-full border border-primary/20 uppercase tracking-widest mb-6 shadow-sm">
                <span class="size-2 rounded-full bg-primary animate-pulse"></span>
                Legal
            </div>
            <h1 class="text-4xl lg:text-5xl font-semibold text-base-content tracking-tight mb-4">Privacy Policy</h1>
            <p class="text-[13px] text-base-content/40 font-medium mb-6">Last updated: April 14, 2026</p>
            <p class="text-[15px] text-base-content/60 font-medium leading-relaxed max-w-2xl">
                At Diamonds & Pearls Catering, we take your privacy seriously. This policy explains how we collect, use, and protect your personal information when you use our website and services.
            </p>
        </div>
    </section>

    {{-- Table of Contents --}}
    <section class="bg-base-100 py-10 border-b border-base-content/10">
        <div class="container mx-auto px-4 lg:px-8 max-w-4xl">
            <div class="text-[11px] font-bold uppercase tracking-[0.2em] text-base-content/40 mb-5">In This Document</div>
            <ol class="grid sm:grid-cols-2 lg:grid-cols-3 gap-3">
                @foreach([
                    ['id' => 'privacy-1', 'label' => 'Information Collection'],
                    ['id' => 'privacy-2', 'label' => 'Data Usage'],
                    ['id' => 'privacy-3', 'label' => 'Information Protection'],
                    ['id' => 'privacy-4', 'label' => 'Cookies & Analytics'],
                    ['id' => 'privacy-5', 'label' => 'Your Rights'],
                    ['id' => 'privacy-6', 'label' => 'Data Retention'],
                    ['id' => 'privacy-7', 'label' => 'Policy Updates'],
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
    <div id="privacy-1" class="scroll-mt-24 bg-base-200 border-b border-base-content/10">
        <div class="container mx-auto px-4 lg:px-8 max-w-4xl py-10 lg:py-12">
            <div class="flex items-center gap-3 mb-4">
                <div class="size-8 bg-primary/10 rounded-lg flex items-center justify-center text-primary text-[13px] font-bold shrink-0">1</div>
                <h2 class="text-xl font-bold text-base-content">Information Collection</h2>
            </div>
            <p class="text-[15px] text-base-content/60 font-medium leading-relaxed pl-11">
                We collect essential information to facilitate your catering requests, including your name, telephone number, email address, and event specifics. This data is collected directly from you when you register, place a booking, or contact us through our website.
            </p>
        </div>
    </div>

    <div id="privacy-2" class="scroll-mt-24 bg-base-100 border-b border-base-content/10">
        <div class="container mx-auto px-4 lg:px-8 max-w-4xl py-10 lg:py-12">
            <div class="flex items-center gap-3 mb-4">
                <div class="size-8 bg-primary/10 rounded-lg flex items-center justify-center text-primary text-[13px] font-bold shrink-0">2</div>
                <h2 class="text-xl font-bold text-base-content">Data Usage</h2>
            </div>
            <p class="text-[15px] text-base-content/60 font-medium leading-relaxed pl-11">
                Your information is used to process your bookings, manage payments, and communicate event logistics. We do not sell or lease your personal identifiers to third-party marketing entities. We may share your data with service providers (such as payment processors) strictly where necessary to fulfil your booking.
            </p>
        </div>
    </div>

    <div id="privacy-3" class="scroll-mt-24 bg-base-200 border-b border-base-content/10">
        <div class="container mx-auto px-4 lg:px-8 max-w-4xl py-10 lg:py-12">
            <div class="flex items-center gap-3 mb-4">
                <div class="size-8 bg-primary/10 rounded-lg flex items-center justify-center text-primary text-[13px] font-bold shrink-0">3</div>
                <h2 class="text-xl font-bold text-base-content">Information Protection</h2>
            </div>
            <p class="text-[15px] text-base-content/60 font-medium leading-relaxed pl-11">
                We implement industry-standard security protocols to safeguard your data. Digital payments are processed securely through <strong class="text-base-content">Moolre Payments</strong> — an encrypted payment gateway. Card and payment details are not stored on our servers and are handled entirely by Moolre in accordance with their security standards.
            </p>
        </div>
    </div>

    <div id="privacy-4" class="scroll-mt-24 bg-base-100 border-b border-base-content/10">
        <div class="container mx-auto px-4 lg:px-8 max-w-4xl py-10 lg:py-12">
            <div class="flex items-center gap-3 mb-4">
                <div class="size-8 bg-primary/10 rounded-lg flex items-center justify-center text-primary text-[13px] font-bold shrink-0">4</div>
                <h2 class="text-xl font-bold text-base-content">Cookies & Analytics</h2>
            </div>
            <div class="pl-11 space-y-3 text-[15px] text-base-content/60 font-medium leading-relaxed">
                <p>
                    Our platform uses essential cookies to manage your booking session and provide a seamless navigation experience. We may use anonymised analytics to understand how visitors use our site and improve our services.
                </p>
                <p>
                    Essential cookies cannot be disabled as they are required for the site to function. Analytics cookies are anonymised and do not identify you personally. If you wish to opt out of analytics, you may clear your browser cookies at any time.
                </p>
            </div>
        </div>
    </div>

    <div id="privacy-5" class="scroll-mt-24 bg-base-200 border-b border-base-content/10">
        <div class="container mx-auto px-4 lg:px-8 max-w-4xl py-10 lg:py-12">
            <div class="flex items-center gap-3 mb-4">
                <div class="size-8 bg-primary/10 rounded-lg flex items-center justify-center text-primary text-[13px] font-bold shrink-0">5</div>
                <h2 class="text-xl font-bold text-base-content">Your Rights</h2>
            </div>
            <div class="pl-11 space-y-4 text-[15px] text-base-content/60 font-medium leading-relaxed">
                <p>You have the right to:</p>
                <ul class="list-disc pl-6 space-y-2">
                    <li><strong class="text-base-content">Access</strong> the personal data we hold about you.</li>
                    <li><strong class="text-base-content">Correct</strong> any inaccurate or incomplete information.</li>
                    <li><strong class="text-base-content">Request deletion</strong> of your personal data, subject to any legal obligations we must fulfil.</li>
                    <li><strong class="text-base-content">Object</strong> to the processing of your data for marketing purposes.</li>
                </ul>
                <p>
                    To exercise any of these rights, please contact us at <a href="mailto:graceayesu@yahoo.com" class="text-primary hover:underline">graceayesu@yahoo.com</a> with your request. We will respond within 30 days.
                </p>
            </div>
        </div>
    </div>

    <div id="privacy-6" class="scroll-mt-24 bg-base-100 border-b border-base-content/10">
        <div class="container mx-auto px-4 lg:px-8 max-w-4xl py-10 lg:py-12">
            <div class="flex items-center gap-3 mb-4">
                <div class="size-8 bg-primary/10 rounded-lg flex items-center justify-center text-primary text-[13px] font-bold shrink-0">6</div>
                <h2 class="text-xl font-bold text-base-content">Data Retention</h2>
            </div>
            <p class="text-[15px] text-base-content/60 font-medium leading-relaxed pl-11">
                We retain your personal data for as long as necessary to fulfil the purposes outlined in this policy. Booking records and associated customer data are retained for a minimum of <strong class="text-base-content">2 years</strong> for accounting and dispute-resolution purposes, after which they are securely deleted or anonymised. You may request early deletion at any time, subject to any legal obligations.
            </p>
        </div>
    </div>

    <div id="privacy-7" class="scroll-mt-24 bg-base-200">
        <div class="container mx-auto px-4 lg:px-8 max-w-4xl py-10 lg:py-12">
            <div class="flex items-center gap-3 mb-4">
                <div class="size-8 bg-primary/10 rounded-lg flex items-center justify-center text-primary text-[13px] font-bold shrink-0">7</div>
                <h2 class="text-xl font-bold text-base-content">Policy Updates</h2>
            </div>
            <p class="text-[15px] text-base-content/60 font-medium leading-relaxed pl-11">
                Diamonds & Pearls Catering reserves the right to modify this policy as our platform evolves. Any significant changes will be reflected with an updated "Last Updated" date at the top of this page. We encourage you to review this policy periodically.
            </p>
        </div>
    </div>

    {{-- Bottom CTA --}}
    <section class="bg-base-100 py-16 lg:py-20 border-t border-base-content/10">
        <div class="container mx-auto px-4 lg:px-8 max-w-4xl text-center">
            <p class="text-[15px] text-base-content/60 font-medium mb-6">Have concerns about your privacy?</p>
            <div class="flex flex-wrap items-center justify-center gap-4">
                <x-ui.button href="{{ route('contact') }}" variant="secondary" size="md">
                    Contact Us
                </x-ui.button>
                <a href="https://wa.me/{{ $whatsappNumber }}" target="_blank"
                   class="inline-flex items-center justify-center gap-2 px-[18px] py-[10px] text-[13px] font-medium rounded-xl bg-[#25D366] text-white hover:brightness-110 transition-all">
                    <svg class="size-4" viewBox="0 0 24 24" fill="currentColor"><path d="M12.031 6.172c-3.181 0-5.767 2.586-5.768 5.766-.001 1.298.38 2.27 1.019 3.287l-.582 2.128 2.182-.573c.978.58 1.911.928 3.145.929 3.178 0 5.767-2.587 5.768-5.766.001-3.187-2.575-5.77-5.764-5.771zm3.392 8.244c-.144.405-.837.774-1.17.824-.299.045-.677.063-1.092-.069-.252-.08-.573-.187-.988-.365-1.739-.751-2.874-2.502-2.961-2.617-.087-.116-.708-.94-.708-1.793s.448-1.273.607-1.446c.159-.173.346-.217.462-.217l.332.006c.106.005.249-.04.39.298.144.347.491 1.2.534 1.287.043.087.072.188.014.304-.058.116-.087.188-.173.289l-.26.304c-.087.086-.177.18-.076.354.101.174.449.741.964 1.201.662.591 1.221.774 1.394.86s.274.072.376-.043c.101-.116.433-.506.549-.68.116-.173.231-.145.39-.087s1.011.477 1.184.564.289.13.332.202c.045.072.045.419-.1.824zm-3.423-14.416c-6.627 0-12 5.373-12 12s5.373 12 12 12 12-5.373 12-12-5.373-12-12-12zm.082 21.183c-1.653 0-3.331-.482-4.717-1.3l-5.365 1.488 1.474-5.26c-.822-1.391-1.309-3.093-1.309-4.821 0-5.319 4.316-9.635 9.636-9.635 5.316 0 9.632 4.316 9.632 9.635 0 5.317-4.316 9.631-9.351 9.631z"/></svg>
                    WhatsApp
                </a>
            </div>
        </div>
    </section>
</x-guest-layout>
