<x-guest-layout title="Privacy Policy">
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
            <h1 class="text-4xl sm:text-5xl lg:text-6xl font-semibold text-white tracking-tight mb-3 leading-tight">Privacy Policy</h1>
            <p class="text-[13px] text-white/50 font-medium mb-6">Last updated: April 14, 2026</p>
            <p class="text-[15px] text-white/70 font-medium leading-relaxed max-w-2xl">
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
    <section class="bg-[#18542A] py-16 lg:py-20 relative overflow-hidden">
        <div class="absolute top-0 right-0 size-[450px] bg-white/5 blur-[100px] rounded-full -translate-y-1/3 translate-x-1/3" aria-hidden="true"></div>
        <div class="absolute bottom-0 left-0 size-[350px] bg-black/15 blur-[80px] rounded-full translate-y-1/3 -translate-x-1/4" aria-hidden="true"></div>
        <div class="absolute top-1/2 left-1/2 size-[500px] bg-white/3 blur-[100px] rounded-full -translate-x-1/2 -translate-y-1/2" aria-hidden="true"></div>
        {{-- Floating icons --}}
        <div class="absolute top-4 left-8 text-white/8 hidden lg:block -rotate-6" aria-hidden="true">
            <svg xmlns="http://www.w3.org/2000/svg" class="size-20" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="0.9"><path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
        </div>
        <div class="absolute bottom-4 right-8 text-white/6 hidden lg:block rotate-12" aria-hidden="true">
            <svg xmlns="http://www.w3.org/2000/svg" class="size-24" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="0.8"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
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
