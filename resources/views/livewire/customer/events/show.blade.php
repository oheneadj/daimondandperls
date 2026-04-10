<div>
    {{-- Header --}}
    <div class="mb-6 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div class="flex items-center gap-3 sm:gap-4">
            <a href="{{ route('dashboard.events.index') }}" wire:navigate class="size-10 bg-base-200 rounded-xl flex items-center justify-center text-base-content/60 hover:text-base-content hover:bg-base-200/80 transition-all shrink-0">
                <svg xmlns="http://www.w3.org/2000/svg" class="size-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
            </a>
            <div class="min-w-0">
                <h1 class="text-xl sm:text-2xl font-semibold text-base-content truncate">{{ $booking->reference }}</h1>
                <p class="text-[13px] text-base-content/60 font-medium mt-0.5">Event Details</p>
            </div>
        </div>

        @php
            $statusConfig = match($booking->status) {
                \App\Enums\BookingStatus::Pending => ['color' => 'bg-[#FFC926] text-black', 'label' => 'Pending'],
                \App\Enums\BookingStatus::Confirmed => ['color' => 'bg-[#18542A] text-white', 'label' => 'Confirmed'],
                \App\Enums\BookingStatus::Completed => ['color' => 'bg-base-content/20 text-base-content/60', 'label' => 'Completed'],
                \App\Enums\BookingStatus::Cancelled => ['color' => 'bg-[#D52518] text-white', 'label' => 'Cancelled'],
                default => ['color' => 'bg-base-200 text-base-content/60', 'label' => str($booking->status?->value ?? 'unknown')->title()->replace('_', ' ')],
            };
        @endphp
        <span class="self-start px-4 py-2 rounded-full {{ $statusConfig['color'] }} text-[11px] font-black uppercase tracking-widest">
            {{ $statusConfig['label'] }}
        </span>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Main Content --}}
        <div class="lg:col-span-2 space-y-6">
            {{-- Event Details --}}
            <div class="bg-white border border-base-content/10 rounded-2xl p-5 sm:p-6 shadow-sm">
                <h2 class="text-[11px] font-bold text-primary uppercase tracking-widest mb-5">Event Details</h2>
                <div class="grid grid-cols-2 sm:grid-cols-3 gap-5">
                    <div>
                        <div class="text-[11px] font-bold text-base-content/40 uppercase tracking-wide mb-1">Event Type</div>
                        <div class="text-[15px] font-semibold text-base-content">
                            @if($booking->event_type)
                                {{ $booking->event_type === \App\Enums\EventType::Other ? ($booking->event_type_other ?: 'Other') : $booking->event_type->name }}
                            @else
                                Not specified
                            @endif
                        </div>
                    </div>
                    <div>
                        <div class="text-[11px] font-bold text-base-content/40 uppercase tracking-wide mb-1">Date</div>
                        <div class="text-[15px] font-semibold text-base-content">
                            {{ $booking->event_date ? $booking->event_date->format('l, F j, Y') : 'To be decided' }}
                        </div>
                    </div>
                    <div>
                        <div class="text-[11px] font-bold text-base-content/40 uppercase tracking-wide mb-1">Time</div>
                        <div class="text-[15px] font-semibold text-base-content">
                            @if($booking->event_start_time)
                                {{ \Carbon\Carbon::parse($booking->event_start_time)->format('g:i A') }}
                                @if($booking->event_end_time)
                                    - {{ \Carbon\Carbon::parse($booking->event_end_time)->format('g:i A') }}
                                @endif
                            @else
                                To be decided
                            @endif
                        </div>
                    </div>
                    <div>
                        <div class="text-[11px] font-bold text-base-content/40 uppercase tracking-wide mb-1">Guests</div>
                        <div class="text-[15px] font-semibold text-base-content">{{ $booking->pax ?? 'Not specified' }}</div>
                    </div>
                    <div>
                        <div class="text-[11px] font-bold text-base-content/40 uppercase tracking-wide mb-1">Service Style</div>
                        <div class="text-[15px] font-semibold text-base-content">{{ $booking->is_buffet ? 'Buffet' : 'Plates' }}</div>
                    </div>
                    @if($booking->event_location)
                    <div class="col-span-2 sm:col-span-3">
                        <div class="text-[11px] font-bold text-base-content/40 uppercase tracking-wide mb-1">Location</div>
                        <div class="text-[15px] font-semibold text-base-content">{{ $booking->event_location }}</div>
                    </div>
                    @endif
                    <div>
                        <div class="text-[11px] font-bold text-base-content/40 uppercase tracking-wide mb-1">Booked On</div>
                        <div class="text-[15px] font-semibold text-base-content">{{ $booking->created_at->format('M j, Y') }}</div>
                    </div>
                </div>
            </div>

        </div>

        {{-- Sidebar --}}
        <div class="space-y-6">
            {{-- Payment --}}
            <div class="bg-white border border-base-content/10 rounded-2xl p-5 sm:p-6 shadow-sm">
                <h2 class="text-[11px] font-bold text-primary uppercase tracking-widest mb-5">Payment</h2>

                @if($booking->total_amount > 0)
                    @php
                        $paymentColor = match($booking->payment_status) {
                            \App\Enums\PaymentStatus::Paid => 'text-success',
                            \App\Enums\PaymentStatus::Unpaid => 'text-error',
                            \App\Enums\PaymentStatus::Pending => 'text-dp-warning',
                            default => 'text-base-content/60',
                        };
                    @endphp
                    <div class="space-y-4">
                        <div class="flex items-center justify-between">
                            <span class="text-[12px] font-medium text-base-content/50">Status</span>
                            <span class="text-[13px] font-bold {{ $paymentColor }} uppercase">{{ $booking->payment_status->value }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-[12px] font-medium text-base-content/50">Amount</span>
                            <span class="text-[13px] font-semibold text-base-content">GH&#8373; {{ number_format($booking->total_amount, 2) }}</span>
                        </div>
                        @if($booking->payment)
                            <div class="flex items-center justify-between">
                                <span class="text-[12px] font-medium text-base-content/50">Method</span>
                                <span class="text-[13px] font-semibold text-base-content">{{ $booking->payment->method?->name ?? '-' }}</span>
                            </div>
                            @if($booking->payment->paid_at)
                                <div class="flex items-center justify-between">
                                    <span class="text-[12px] font-medium text-base-content/50">Paid On</span>
                                    <span class="text-[13px] font-semibold text-base-content">{{ \Carbon\Carbon::parse($booking->payment->paid_at)->format('M j, Y') }}</span>
                                </div>
                            @endif
                        @endif
                    </div>

                    <div class="mt-6 space-y-3">
                        @if($booking->payment_status === \App\Enums\PaymentStatus::Unpaid)
                            <a href="{{ route('booking.payment', $booking->reference) }}" class="flex items-center justify-center gap-2 w-full px-4 py-3 bg-primary text-white rounded-xl font-semibold text-[13px] hover:bg-primary/90 transition-colors shadow-sm min-h-[44px]">
                                Pay Now
                            </a>
                        @endif
                        @if($booking->payment_status === \App\Enums\PaymentStatus::Paid)
                            <a href="{!! app(\App\Services\InvoiceService::class)->getDownloadUrl($booking) !!}" target="_blank" class="flex items-center justify-center gap-2 w-full px-4 py-3 bg-base-200 text-base-content rounded-xl font-semibold text-[13px] hover:bg-base-200/80 transition-colors min-h-[44px]">
                                <svg xmlns="http://www.w3.org/2000/svg" class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                                Download Invoice
                            </a>
                        @endif
                    </div>
                @else
                    <div class="text-center py-4">
                        <div class="size-12 bg-base-200 rounded-full flex items-center justify-center mx-auto mb-3 text-base-content/30">
                            <svg xmlns="http://www.w3.org/2000/svg" class="size-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                            </svg>
                        </div>
                        <p class="text-[13px] font-semibold text-base-content/50">Quote Pending</p>
                        <p class="text-[12px] text-base-content/40 mt-1">We'll contact you with pricing details.</p>
                    </div>
                @endif
            </div>

            {{-- Contact Info --}}
            <div class="bg-white border border-base-content/10 rounded-2xl p-5 sm:p-6 shadow-sm">
                <h2 class="text-[11px] font-bold text-primary uppercase tracking-widest mb-5">Contact Info</h2>
                <div class="space-y-3">
                    <div>
                        <div class="text-[11px] font-bold text-base-content/40 uppercase tracking-wide mb-1">Name</div>
                        <div class="text-[14px] font-semibold text-base-content">{{ $booking->customer->name }}</div>
                    </div>
                    <div>
                        <div class="text-[11px] font-bold text-base-content/40 uppercase tracking-wide mb-1">Phone</div>
                        <div class="text-[14px] font-semibold text-base-content">{{ $booking->customer->phone }}</div>
                    </div>
                    @if($booking->customer->email)
                        <div>
                            <div class="text-[11px] font-bold text-base-content/40 uppercase tracking-wide mb-1">Email</div>
                            <div class="text-[14px] font-semibold text-base-content">{{ $booking->customer->email }}</div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
