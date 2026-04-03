<x-guest-layout title="Event Inquiry Received">
    <div class="bg-base-200 min-h-screen py-10 lg:py-24 px-4 overflow-hidden relative">
        {{-- Decorative background elements --}}
        <div class="absolute top-0 right-0 size-[600px] bg-primary/5 blur-[120px] rounded-full -translate-y-1/2 translate-x-1/2 -z-10"></div>
        <div class="absolute bottom-0 left-0 size-[400px] bg-secondary/5 blur-[120px] rounded-full translate-y-1/2 -translate-x-1/2 -z-10"></div>

        <div class="container mx-auto max-w-3xl">
            {{-- Success Icon --}}
            <div class="text-center mb-12 animate-fade-in-up">
                <div class="size-24 bg-[#18542A]/10 rounded-full flex items-center justify-center mx-auto mb-8 ring-8 ring-[#18542A]/5">
                    <svg xmlns="http://www.w3.org/2000/svg" class="size-12 text-[#18542A]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>

                <h1 class="text-3xl lg:text-5xl font-semibold text-base-content tracking-tight mb-4">Event Inquiry Received</h1>
                <p class="text-lg text-base-content/60 max-w-xl mx-auto leading-relaxed">
       We've received your event details and our team will be in touch shortly.
                </p>
            </div>

            <x-ui.card class="overflow-hidden border-base-content/10 shadow-xl rounded-[32px] mb-10">
                {{-- Reference Header --}}
                <div class="bg-[#18542A] text-white p-8 lg:p-10 text-center">
                    <p class="text-[11px] font-black uppercase tracking-[0.3em] opacity-70 mb-3">Your Reference Number</p>
                    <div class="text-3xl lg:text-4xl font-bold tracking-[0.1em]">{{ $booking->reference }}</div>
                    <p class="text-[12px] opacity-70 mt-3 font-medium">Keep this reference for tracking your inquiry</p>
                </div>

                <div class="p-8 lg:p-12 space-y-10 bg-base-100">
                    {{-- Event Details Summary --}}
                    <div>
                        <h4 class="text-[11px] font-black uppercase tracking-[0.3em] text-base-content/60 mb-8 border-b border-base-content/10 pb-4">Your Event Details</h4>

                        <div class="grid gap-6">
                            @if($booking->event_date)
                                <div class="flex items-center gap-5">
                                    <div class="size-12 rounded-2xl bg-base-200 flex items-center justify-center shadow-sm shrink-0">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="size-5 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                                    </div>
                                    <div>
                                        <p class="text-[10px] font-bold text-base-content/40 uppercase tracking-widest">Date</p>
                                        <p class="text-lg font-bold text-base-content">{{ $booking->event_date->format('l, F j, Y') }}</p>
                                    </div>
                                </div>
                            @endif

                            @if($booking->event_start_time)
                                <div class="flex items-center gap-5">
                                    <div class="size-12 rounded-2xl bg-base-200 flex items-center justify-center shadow-sm shrink-0">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="size-5 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                    </div>
                                    <div>
                                        <p class="text-[10px] font-bold text-base-content/40 uppercase tracking-widest">Time</p>
                                        <p class="text-lg font-bold text-base-content">
                                            {{ \Carbon\Carbon::parse($booking->event_start_time)->format('g:i A') }}
                                            @if($booking->event_end_time) — {{ \Carbon\Carbon::parse($booking->event_end_time)->format('g:i A') }} @endif
                                        </p>
                                    </div>
                                </div>
                            @endif

                            @if($booking->event_type)
                                <div class="flex items-center gap-5">
                                    <div class="size-12 rounded-2xl bg-base-200 flex items-center justify-center shadow-sm shrink-0">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="size-5 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" /></svg>
                                    </div>
                                    <div>
                                        <p class="text-[10px] font-bold text-base-content/40 uppercase tracking-widest">Event Type</p>
                                        <p class="text-lg font-bold text-base-content">
                                            {{ $booking->event_type->value === 'other' ? $booking->event_type_other : str($booking->event_type->value)->title()->replace('_', ' ') }}
                                        </p>
                                    </div>
                                </div>
                            @endif

                            @if($booking->pax)
                                <div class="flex items-center gap-5">
                                    <div class="size-12 rounded-2xl bg-base-200 flex items-center justify-center shadow-sm shrink-0">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="size-5 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                                    </div>
                                    <div>
                                        <p class="text-[10px] font-bold text-base-content/40 uppercase tracking-widest">Guests</p>
                                        <p class="text-lg font-bold text-base-content">{{ $booking->pax }} {{ $booking->is_buffet ? 'guests (Buffet)' : 'plates (Fixed)' }}</p>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>

                    {{-- Menu Selections --}}
                    @if($booking->items->isNotEmpty())
                        <div class="pt-6 border-t border-base-content/10">
                            <h4 class="text-[11px] font-black uppercase tracking-[0.3em] text-base-content/60 mb-6">Menu Suggestions</h4>
                            <div class="flex flex-wrap gap-2">
                                @foreach($booking->items as $item)
                                    <span class="inline-flex items-center px-4 py-2 rounded-full bg-base-200 border border-base-content/10 text-[13px] font-bold text-base-content">
                                        {{ $item->package_name ?? $item->package?->name ?? 'Package' }}
                                    </span>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    {{-- What Happens Next --}}
                    <div class="pt-6 border-t border-base-content/10">
                        <h4 class="text-[11px] font-black uppercase tracking-[0.3em] text-base-content/60 mb-6">What Happens Next</h4>

                        <div class="space-y-5">
                            <div class="flex items-start gap-4">
                                <div class="size-8 rounded-full bg-primary/10 flex items-center justify-center shrink-0 mt-0.5">
                                    <span class="text-[12px] font-black text-primary">1</span>
                                </div>
                                <div>
                                    <p class="text-[14px] font-bold text-base-content">We review your event details</p>
                                    <p class="text-[12px] text-base-content/50 font-medium mt-0.5">Our team will go through your requirements and prepare a tailored quote.</p>
                                </div>
                            </div>

                            <div class="flex items-start gap-4">
                                <div class="size-8 rounded-full bg-primary/10 flex items-center justify-center shrink-0 mt-0.5">
                                    <span class="text-[12px] font-black text-primary">2</span>
                                </div>
                                <div>
                                    <p class="text-[14px] font-bold text-base-content">You receive your quote</p>
                                    <p class="text-[12px] text-base-content/50 font-medium mt-0.5">We'll send you a detailed quote via SMS{{ $booking->customer->email ? ' and email' : '' }} with a secure payment link.</p>
                                </div>
                            </div>

                            <div class="flex items-start gap-4">
                                <div class="size-8 rounded-full bg-primary/10 flex items-center justify-center shrink-0 mt-0.5">
                                    <span class="text-[12px] font-black text-primary">3</span>
                                </div>
                                <div>
                                    <p class="text-[14px] font-bold text-base-content">Confirm and pay</p>
                                    <p class="text-[12px] text-base-content/50 font-medium mt-0.5">Once you're happy with the quote, complete payment online or arrange it directly with us.</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Contact Info --}}
                    <div class="bg-base-200 rounded-2xl p-6 border border-base-content/10">
                        <p class="text-[13px] text-base-content/60 font-medium leading-relaxed">
                            Our team will contact you on <span class="text-base-content font-bold">{{ $booking->customer->phone }}</span>{{ $booking->customer->email ? ' and ' . $booking->customer->email : '' }} to confirm your event details. If you have any questions in the meantime, feel free to reach out to us.
                        </p>
                    </div>

                    {{-- Actions --}}
                    <div class="pt-4 flex flex-col sm:flex-row justify-center gap-4">
                        <x-ui.button :href="route('home')" variant="primary" size="lg" class="px-10 h-14 shadow-xl rounded-2xl text-[14px] font-black tracking-widest uppercase">
                            Back to Home
                        </x-ui.button>

                        <x-ui.button :href="route('packages.browse')" variant="ghost" size="lg" class="px-10 h-14 rounded-2xl text-[14px] font-bold">
                            Browse Our Menu
                        </x-ui.button>
                    </div>
                </div>
            </x-ui.card>

            <div class="text-center opacity-40">
                <p class="text-[11px] font-black uppercase tracking-[0.5em] text-base-content/60">Diamonds & Pearls Catering — Excellence in Every Detail</p>
            </div>
        </div>
    </div>
</x-guest-layout>
