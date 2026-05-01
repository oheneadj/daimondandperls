<div wire:poll.5s="checkConfirmation" class="min-h-screen bg-base-200 py-8 lg:py-14 px-4">
    <div class="container mx-auto max-w-xl">

        {{-- Progress steps --}}
        <div class="mb-10">
            <div class="flex items-center justify-between relative max-w-xs mx-auto sm:max-w-sm">
                <div class="absolute top-5 left-0 w-full h-0.5 bg-primary -z-10"></div>
                @foreach(['Details', 'Payment', 'Done'] as $i => $step)
                    <div class="flex flex-col items-center gap-2.5">
                        <div @class([
                            'size-10 rounded-full flex items-center justify-center font-bold text-white transition-all duration-500',
                            'bg-primary shadow-lg scale-110 ring-4 ring-primary/20' => $i === 2,
                            'bg-primary' => $i < 2,
                        ])>
                            <svg xmlns="http://www.w3.org/2000/svg" class="size-5" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <span @class([
                            'text-[10px] uppercase tracking-widest font-bold',
                            'text-primary'         => $i === 2,
                            'text-base-content/50' => $i < 2,
                        ])>{{ $step }}</span>
                    </div>
                @endforeach
            </div>
        </div>

        {{-- Hero --}}
        <div class="text-center mb-8">
            {{-- Animated pulse ring --}}
            <div class="relative flex items-center justify-center mx-auto mb-5 w-16 h-16">
                <span class="absolute inline-flex size-16 rounded-full bg-primary/20 animate-ping"></span>
                <span class="absolute inline-flex size-12 rounded-full bg-primary/10 animate-ping" style="animation-delay: 0.3s; animation-duration: 1.8s;"></span>
                <div class="relative size-16 rounded-full bg-primary/10 flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="size-8 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
            <h1 class="text-[26px] font-semibold text-base-content tracking-tight">Payment Received!</h1>
            <p class="text-[14px] text-base-content/50 mt-1.5 max-w-sm mx-auto">
                Thanks, <span class="text-base-content font-semibold">{{ $booking->customer->name }}</span>. We're waiting for admin confirmation.
            </p>

            {{-- Animated dots --}}
            <div class="flex items-center justify-center gap-1.5 mt-4">
                <span class="size-1.5 rounded-full bg-primary animate-bounce" style="animation-delay: 0s;"></span>
                <span class="size-1.5 rounded-full bg-primary animate-bounce" style="animation-delay: 0.15s;"></span>
                <span class="size-1.5 rounded-full bg-primary animate-bounce" style="animation-delay: 0.3s;"></span>
            </div>
        </div>

        {{-- Card --}}
        <div class="bg-base-100 rounded-2xl border border-base-content/5 shadow-sm overflow-hidden mb-5">

            {{-- Reference strip --}}
            <div class="bg-primary/5 border-b border-primary/10 px-6 py-5 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                <div class="min-w-0">
                    <p class="text-[10px] font-bold uppercase tracking-widest text-base-content/40 mb-0.5">Booking Reference</p>
                    <p class="text-[18px] font-bold text-base-content tracking-wide break-all">{{ $booking->reference }}</p>
                </div>
                <span class="self-start sm:self-auto shrink-0 flex items-center gap-1.5 px-3 py-1.5 rounded-full bg-[#FFC926]/15 border border-[#FFC926]/30 text-[#B08A00] text-[11px] font-black uppercase tracking-wider">
                    <span class="size-1.5 rounded-full bg-[#B08A00] animate-pulse"></span>
                    Awaiting Confirmation
                </span>
            </div>

            <div class="p-6 space-y-5">

                {{-- What happens next --}}
                <div class="space-y-3">
                    <p class="text-[12px] font-bold uppercase tracking-widest text-base-content/40">What Happens Next</p>
                    <div class="space-y-4">
                        <div class="flex items-start gap-3">
                            <div class="size-6 rounded-full bg-primary/10 flex items-center justify-center shrink-0 mt-0.5">
                                <span class="text-[11px] font-black text-primary">1</span>
                            </div>
                            <p class="text-[13px] text-base-content/70 leading-relaxed">Our team reviews your transfer and matches it to your booking.</p>
                        </div>
                        <div class="flex items-start gap-3">
                            <div class="size-6 rounded-full bg-primary/10 flex items-center justify-center shrink-0 mt-0.5">
                                <span class="text-[11px] font-black text-primary">2</span>
                            </div>
                            <p class="text-[13px] text-base-content/70 leading-relaxed">Once confirmed, you'll receive an SMS or a call from <strong>{{ dpc_setting('business_phone') }}</strong>. This page will also update automatically.</p>
                        </div>
                        <div class="flex items-start gap-3">
                            <div class="size-6 rounded-full bg-primary/10 flex items-center justify-center shrink-0 mt-0.5">
                                <span class="text-[11px] font-black text-primary">3</span>
                            </div>
                            <p class="text-[13px] text-base-content/70 leading-relaxed">Your booking is locked in and we begin preparing your order.</p>
                        </div>
                    </div>
                </div>

                {{-- Transfer details --}}
                @if(dpc_setting('business_momo_number'))
                <div class="rounded-xl border border-base-content/8 overflow-hidden">
                    <div class="bg-base-200/60 px-4 py-2.5 border-b border-base-content/8">
                        <p class="text-[10px] font-bold uppercase tracking-widest text-base-content/40">Transfer Was Sent To</p>
                    </div>
                    @if(dpc_setting('business_momo_name'))
                    <div class="flex items-center justify-between px-4 py-3 border-b border-base-content/5">
                        <span class="text-xs text-base-content/50 font-medium">Account Name</span>
                        <span class="text-sm font-bold text-base-content">{{ dpc_setting('business_momo_name') }}</span>
                    </div>
                    @endif
                    @if(dpc_setting('business_momo_network'))
                    <div class="flex items-center justify-between px-4 py-3 border-b border-base-content/5">
                        <span class="text-xs text-base-content/50 font-medium">Network</span>
                        <span class="text-sm font-bold text-base-content">{{ match(dpc_setting('business_momo_network')) { '13' => 'MTN', '6' => 'Vodafone', '7' => 'AirtelTigo', default => dpc_setting('business_momo_network') } }}</span>
                    </div>
                    @endif
                    <div class="flex items-center justify-between px-4 py-3">
                        <span class="text-xs text-base-content/50 font-medium">MoMo Number</span>
                        <span class="text-sm font-bold text-base-content">{{ dpc_setting('business_momo_number') }}</span>
                    </div>
                </div>
                @endif

                {{-- Amount --}}
                <div class="flex items-center justify-between pt-4 border-t border-dashed border-base-content/10">
                    <span class="text-[13px] text-base-content/50 font-medium">Amount</span>
                    <span class="text-[22px] font-bold text-primary">GH₵ {{ number_format($booking->total_amount, 0) }}</span>
                </div>

                {{-- Contact note --}}
                <div class="flex items-start gap-3 bg-base-200 rounded-xl p-4">
                    <svg xmlns="http://www.w3.org/2000/svg" class="size-4 text-primary shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                    </svg>
                    <p class="text-[12px] text-base-content/60 leading-relaxed">
                        We'll reach you on <span class="text-base-content font-semibold">{{ $booking->customer->phone }}</span>. Keep your phone nearby.
                    </p>
                </div>

            </div>
        </div>

        <p class="text-center text-[11px] text-base-content/30 font-medium">
            Questions? Call or WhatsApp us on {{ dpc_setting('business_whatsapp') ?: dpc_setting('business_phone') }}
        </p>

    </div>
</div>
