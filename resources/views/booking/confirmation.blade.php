<x-guest-layout title="Booking Confirmed">
    <div class="min-h-screen bg-base-200 py-8 lg:py-14 px-4">
        <div class="container mx-auto max-w-xl">

            {{-- Progress: all steps complete, no back links needed --}}
            <div class="mb-10">
                <div class="flex items-center justify-between relative max-w-xs mx-auto sm:max-w-sm">
                    <div class="absolute top-5 left-0 w-full h-0.5 bg-primary -z-10"></div>
                    @foreach(['Details', 'Payment', 'Done'] as $i => $step)
                        <div class="flex flex-col items-center gap-2.5">
                            <div @class([
                                'size-10 rounded-full flex items-center justify-center font-bold bg-primary text-white transition-all duration-500',
                                'shadow-lg scale-110 ring-4 ring-primary/20' => $i === 2,
                            ])>
                                <svg xmlns="http://www.w3.org/2000/svg" class="size-5" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                            <span @class([
                                'text-[10px] uppercase tracking-widest font-bold',
                                'text-primary'          => $i === 2,
                                'text-base-content/50'  => $i < 2,
                            ])>{{ $step }}</span>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- Hero confirmation --}}
            <div class="text-center mb-8">
                <div class="size-16 rounded-full bg-[#9ABC05]/10 ring-8 ring-[#9ABC05]/5 flex items-center justify-center mx-auto mb-5">
                    <svg xmlns="http://www.w3.org/2000/svg" class="size-8 text-[#5A7A00]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <h1 class="text-[26px] font-semibold text-base-content tracking-tight">Booking Confirmed!</h1>
                <p class="text-[14px] text-base-content/50 mt-1.5 max-w-sm mx-auto">
                    Thanks, <span class="text-base-content font-semibold">{{ $booking->customer->name }}</span>. Your order is in good hands.
                </p>
            </div>

            {{-- Card --}}
            <div class="bg-base-100 rounded-2xl border border-base-content/5 shadow-sm overflow-hidden mb-5">

                {{-- Reference strip --}}
                <div class="bg-[#9ABC05]/10 border-b border-[#9ABC05]/15 px-6 py-5 flex items-center justify-between">
                    <div>
                        <p class="text-[10px] font-bold uppercase tracking-widest text-base-content/40 mb-0.5">Order Reference</p>
                        <p class="text-[22px] font-bold text-base-content tracking-wide">{{ $booking->reference }}</p>
                    </div>
                    @if($booking->payment_status === \App\Enums\PaymentStatus::Paid)
                        <span class="flex items-center gap-1.5 px-3 py-1.5 rounded-full bg-[#9ABC05]/15 border border-[#9ABC05]/30 text-[#5A7A00] text-[11px] font-black uppercase tracking-wider">
                            <span class="size-1.5 rounded-full bg-[#5A7A00]"></span>
                            Paid
                        </span>
                    @else
                        <span class="flex items-center gap-1.5 px-3 py-1.5 rounded-full bg-[#FFC926]/15 border border-[#FFC926]/30 text-[#B08A00] text-[11px] font-black uppercase tracking-wider">
                            <span class="size-1.5 rounded-full bg-[#B08A00]"></span>
                            Pending
                        </span>
                    @endif
                </div>

                <div class="p-6 space-y-5">

                    {{-- Items --}}
                    <div class="space-y-3">
                        @foreach($booking->items as $item)
                            <div class="flex items-center justify-between gap-4">
                                <div>
                                    <p class="text-[14px] font-semibold text-base-content">{{ $item->package_name ?? $item->package?->name ?? 'Package' }}</p>
                                    @if($booking->booking_type !== \App\Enums\BookingType::Event)
                                        <p class="text-[12px] text-base-content/40">Qty {{ $item->quantity }} × GH₵ {{ number_format($item->price, 0) }}</p>
                                    @endif
                                </div>
                                @if($booking->booking_type !== \App\Enums\BookingType::Event)
                                    <span class="text-[14px] font-semibold text-base-content whitespace-nowrap">
                                        GH₵ {{ number_format($item->price * $item->quantity, 0) }}
                                    </span>
                                @endif
                            </div>
                        @endforeach
                    </div>

                    {{-- Total --}}
                    <div class="flex items-center justify-between pt-4 border-t border-dashed border-base-content/10">
                        <span class="text-[13px] text-base-content/50 font-medium">Total Paid</span>
                        <span class="text-[22px] font-bold text-primary">GH₵ {{ number_format($booking->total_amount, 0) }}</span>
                    </div>

                    {{-- Contact note --}}
                    <div class="flex items-start gap-3 bg-base-200 rounded-xl p-4">
                        <svg xmlns="http://www.w3.org/2000/svg" class="size-4 text-primary shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                        </svg>
                        <p class="text-[12px] text-base-content/60 leading-relaxed">
                            We'll contact <span class="text-base-content font-semibold">{{ $booking->customer->phone }}</span> to confirm your delivery details.
                        </p>
                    </div>

                    {{-- Actions --}}
                    <div class="flex flex-col sm:flex-row gap-3 pt-1">
                        @if($booking->payment_status === \App\Enums\PaymentStatus::Paid)
                            <a href="{!! app(\App\Services\InvoiceService::class)->getDownloadUrl($booking) !!}" target="_blank"
                                class="flex-1 inline-flex items-center justify-center gap-2 px-4 py-3 bg-base-200 hover:bg-base-300 text-base-content rounded-xl font-semibold text-[13px] transition-colors">
                                <svg xmlns="http://www.w3.org/2000/svg" class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                                Download Invoice
                            </a>
                        @endif
                        <a href="{{ route('packages.browse') }}"
                            class="flex-1 inline-flex items-center justify-center gap-2 px-4 py-3 bg-primary text-white rounded-xl font-semibold text-[13px] hover:bg-primary/90 transition-colors">
                            Order Again
                        </a>
                    </div>

                    {{-- Delivery details --}}
                    @if($booking->event_date || $booking->delivery_location)
                        <div class="grid grid-cols-2 gap-4 pt-5 border-t border-base-content/10">
                            @if($booking->event_date)
                                <div>
                                    <p class="text-[10px] font-bold uppercase tracking-widest text-base-content/40 mb-1">Date</p>
                                    <p class="text-[13px] font-semibold text-base-content">{{ \Carbon\Carbon::parse($booking->event_date)->format('D, d M Y') }}</p>
                                </div>
                                @if($booking->event_start_time)
                                    <div>
                                        <p class="text-[10px] font-bold uppercase tracking-widest text-base-content/40 mb-1">Time</p>
                                        <p class="text-[13px] font-semibold text-base-content">
                                            {{ \Carbon\Carbon::parse($booking->event_start_time)->format('g:i A') }}
                                            @if($booking->event_end_time) — {{ \Carbon\Carbon::parse($booking->event_end_time)->format('g:i A') }}@endif
                                        </p>
                                    </div>
                                @endif
                            @endif
                            @if($booking->delivery_location)
                                <div class="{{ $booking->event_date ? 'col-span-2' : 'col-span-2' }}">
                                    <p class="text-[10px] font-bold uppercase tracking-widest text-base-content/40 mb-1">Delivery Location</p>
                                    <p class="text-[13px] font-semibold text-base-content">{{ $booking->delivery_location }}</p>
                                </div>
                            @endif
                        </div>
                    @endif
                </div>
            </div>

            {{-- Guest upsell --}}
            @guest
                <div class="bg-base-100 border border-base-content/5 rounded-2xl p-5 flex items-center gap-4 mb-5">
                    <div class="size-10 bg-primary/10 rounded-xl flex items-center justify-center shrink-0">
                        <svg xmlns="http://www.w3.org/2000/svg" class="size-5 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-[14px] font-semibold text-base-content">Save your details for next time</p>
                        <p class="text-[12px] text-base-content/50 mt-0.5">Track bookings, pay faster, view your history.</p>
                    </div>
                    <a href="{{ route('register') }}"
                        class="shrink-0 inline-flex items-center gap-1.5 px-4 py-2 bg-primary text-white text-[12px] font-semibold rounded-lg hover:bg-primary/90 transition-colors whitespace-nowrap">
                        Create Account
                        <svg xmlns="http://www.w3.org/2000/svg" class="size-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </a>
                </div>
            @endguest

            <p class="text-center text-[11px] text-base-content/30 font-medium">
                🔒 Transaction secured with industry-standard encryption
            </p>

        </div>
    </div>

    @if(session('download_invoice'))
        <script>
            window.onload = function () {
                setTimeout(function () {
                    window.open("{!! app(\App\Services\InvoiceService::class)->getDownloadUrl($booking) !!}", "_blank");
                }, 1000);
            };
        </script>
    @endif
</x-guest-layout>
