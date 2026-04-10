<x-guest-layout title="Booking Confirmed">
    <div class="bg-base-200 min-h-screen py-10 lg:py-24 px-4 overflow-hidden relative">
        {{-- Decorative background elements --}}
        <div class="absolute top-0 right-0 size-[600px] bg-primary/5 blur-[120px] rounded-full -translate-y-1/2 translate-x-1/2 -z-10"></div>
        <div class="absolute bottom-0 left-0 size-[400px] bg-secondary/5 blur-[120px] rounded-full translate-y-1/2 -translate-x-1/2 -z-10"></div>

        <div class="container mx-auto max-w-4xl">
            <!-- Progress Bar (5 Steps - Final) -->
            <div class="mb-16 lg:mb-24">
                <div class="flex items-center justify-between relative max-w-3xl mx-auto">
                    {{-- Line connector --}}
                    <div class="absolute top-5 left-0 w-full h-0.5 bg-primary -z-10"></div>

                    @foreach(['Review', 'Contact', 'Event', 'Payment', 'Done'] as $index => $label)
                        @php $stepNum = $index + 1; @endphp
                        <div class="flex flex-col items-center gap-3">
                            <div @class([
                                'size-10 rounded-full flex items-center justify-center  text-sm font-bold transition-all duration-500',
                                'bg-primary text-white shadow-xl scale-110 ring-4 ring-dp-rose-soft' => 5 === $stepNum,
                                'bg-primary text-white' => 5 > $stepNum,
                            ])>
                                <svg xmlns="http://www.w3.org/2000/svg" class="size-5" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <span class="text-[10px] uppercase tracking-[0.15em] font-bold hidden sm:block {{ 5 === $stepNum ? 'text-primary' : 'text-base-content/60' }}">{{ $label }}</span>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="text-center mb-16 animate-fade-in-up">
                
                
                <div class="size-20 bg-[#18542A]/10 rounded-full flex items-center justify-center mx-auto mb-8 ring-8 ring-[#18542A]/5">
                    <svg xmlns="http://www.w3.org/2000/svg" class="size-10 text-[#18542A]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <h1 class=" text-4xl lg:text-6xl font-semibold text-base-content tracking-tight mb-6">Booking Confirmed</h1>
                <p class=" text-xl text-base-content/60  max-w-xl mx-auto leading-relaxed">
                    Thank you for choosing Diamonds & Pearls, <span class="text-primary font-bold not-italic">{{ $booking->customer->name }}</span>. Your order is being meticulously planned.
                </p>
            </div>

            <x-ui.card class="overflow-hidden border-base-content/10 shadow-xl rounded-[32px] mb-16">
                {{-- Success Header --}}
                <div class="bg-[#18542A] text-white p-8 lg:p-14 text-center grid sm:grid-cols-2 gap-12 sm:divide-x divide-white/20">
                    <div class="space-y-3">
                        <p class="text-[11px] font-black uppercase tracking-[0.3em] opacity-70">Order Reference</p>
                        <div class="text-3xl md:text-4xl font-bold tracking-[0.1em]">{{ $booking->reference }}</div>
                    </div>
                    
                    <div class="flex flex-col items-center justify-center space-y-3">
                        <p class="text-[11px] font-black uppercase tracking-[0.3em] opacity-70">Settlement Status</p>
                        @if($booking->payment_status === \App\Enums\PaymentStatus::Paid)
                            <div class="bg-white/20 backdrop-blur-md px-8 py-3 rounded-full font-bold text-xs uppercase tracking-[0.2em] border border-white/30">Confirmed Paid</div>
                        @else
                            <div class="bg-primary/20 backdrop-blur-md px-8 py-3 rounded-full font-bold text-xs uppercase tracking-[0.2em] border border-white/30">Verification Pending</div>
                        @endif
                    </div>
                </div>

                <div class="p-10 lg:p-16 space-y-16 bg-base-100">
                    {{-- Summary Table --}}
                    <div>
                        <h4 class="text-[11px] font-black uppercase tracking-[0.3em] text-base-content/60 mb-10 border-b border-base-content/10 pb-4">Selected Inventory</h4>
                        <div class="space-y-8">
                            @foreach($booking->items as $item)
                            <div class="flex justify-between items-center group">
                                <div class="space-y-1">
                                    <p class=" text-xl font-bold text-base-content group-hover:text-primary transition-colors">{{ $item->package_name ?? $item->package?->name ?? 'Package' }}</p>
                                    <p class="text-[11px] font-bold text-base-content/60 uppercase tracking-widest">Quantity: {{ $item->quantity }}</p>
                                </div>
                                <p class=" text-xl font-bold text-base-content">GH₵ {{ number_format($item->price * $item->quantity, 0) }}</p>
                            </div>
                            @endforeach
                            
                            <div class="pt-8 border-t-2 border-dashed border-base-content/10 flex justify-between items-center">
                                <span class=" text-xl font-semibold text-base-content/60">Grand total</span>
                                <span class=" text-4xl font-bold text-primary">GH₵ {{ number_format($booking->total_amount, 0) }}</span>
                            </div>
                        </div>
                    </div>

                    {{-- Event Meta --}}
                    <div class="grid md:grid-cols-2 gap-16 pt-8 border-t border-base-content/10">
                        <div class="space-y-6">
                            <h4 class="text-[10px] font-black uppercase tracking-[0.3em] text-secondary">Experience Details</h4>
                            <div class="grid gap-6">
                                <div class="flex items-center gap-5 text-base-content">
                                    <div class="size-12 rounded-2xl bg-base-200 flex items-center justify-center shadow-sm">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="size-5 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                                    </div>
                                    <div class=" text-lg font-bold">{{ \Carbon\Carbon::parse($booking->event_date)->format('l, F j, Y') }}</div>
                                </div>
                                <div class="flex items-center gap-5 text-base-content">
                                    <div class="size-12 rounded-2xl bg-base-200 flex items-center justify-center shadow-sm">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="size-5 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                    </div>
                                    <div class=" text-lg font-bold">
                                        {{ \Carbon\Carbon::parse($booking->event_start_time)->format('g:i A') }}
                                        @if($booking->event_end_time) — {{ \Carbon\Carbon::parse($booking->event_end_time)->format('g:i A') }} @endif
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="space-y-6">
                            <h4 class="text-[10px] font-black uppercase tracking-[0.3em] text-secondary">Concierge Note</h4>
                            <p class="text-[15px] text-base-content/60 leading-relaxed font-medium">
                                A personal event coordinator will contact you at <span class="text-base-content font-bold">{{ $booking->customer->phone }}</span> shortly to finalize thematic elements and venue logistics.
                            </p>
                        </div>
                    </div>
                    
                    <div class="pt-10 flex flex-col sm:flex-row justify-center gap-4">
                        @if($booking->payment_status === \App\Enums\PaymentStatus::Paid)
                            <x-ui.button href="{!! app(\App\Services\InvoiceService::class)->getDownloadUrl($booking) !!}" target="_blank" variant="secondary" size="lg" class="px-10 h-16 shadow-lg rounded-2xl text-lg font-bold">
                                <svg xmlns="http://www.w3.org/2000/svg" class="size-6 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                                {{ __('Download Invoice') }}
                            </x-ui.button>
                        @endif

                        <x-ui.button :href="route('home')" variant="primary" size="lg" class="px-10 h-16 shadow-xl rounded-2xl text-lg font-black tracking-widest uppercase">
                            Return to Collection
                        </x-ui.button>
                    </div>

                    @if(session('download_invoice'))
                        <script>
                            window.onload = function() {
                                setTimeout(function() {
                                    window.open("{!! app(\App\Services\InvoiceService::class)->getDownloadUrl($booking) !!}", "_blank");
                                }, 1000);
                            };
                        </script>
                    @endif
                </div>
            </x-ui.card>

            @guest
                <div class="mb-16 bg-primary/5 border border-primary/15 rounded-2xl p-6 sm:p-8 flex flex-col sm:flex-row items-start sm:items-center gap-5">
                    <div class="size-12 bg-primary/10 rounded-xl flex items-center justify-center shrink-0">
                        <svg xmlns="http://www.w3.org/2000/svg" class="size-6 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                    </div>
                    <div class="flex-1">
                        <h3 class="text-[15px] font-bold text-base-content mb-1">Save your details for next time</h3>
                        <p class="text-[13px] text-base-content/60 leading-relaxed">Create a free account to track your bookings, pay faster, and access your booking history.</p>
                    </div>
                    <a href="{{ route('register') }}" class="shrink-0 inline-flex items-center gap-2 px-5 py-2.5 bg-primary text-white text-[13px] font-semibold rounded-lg hover:bg-primary/90 transition-colors">
                        Create Account
                        <svg xmlns="http://www.w3.org/2000/svg" class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </a>
                </div>
            @endguest

            <div class="text-center opacity-40">
                <p class="text-[11px] font-black uppercase tracking-[0.5em] text-base-content/60">Diamonds & Pearls Catering — Excellence in Coordination</p>
            </div>
        </div>
    </div>
</x-guest-layout>
