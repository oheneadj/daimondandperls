<div>
    <!-- Header -->
    <div class="mb-8 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div class="flex items-center gap-4">
            <a href="{{ route('dashboard.bookings.index') }}" wire:navigate class="size-10 bg-base-200 rounded-xl flex items-center justify-center text-base-content/60 hover:text-base-content hover:bg-base-200/80 transition-all">
                <svg xmlns="http://www.w3.org/2000/svg" class="size-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
            </a>
            <div>
                <h1 class="text-2xl font-semibold text-base-content">{{ $booking->reference }}</h1>
                <p class="text-[13px] text-base-content/60 font-medium mt-0.5">Booking Details</p>
            </div>
        </div>

        @php
            $statusConfig = match($booking->status) {
                \App\Enums\BookingStatus::Pending => ['color' => 'bg-dp-warning text-white', 'label' => 'Awaiting Review'],
                \App\Enums\BookingStatus::Confirmed => ['color' => 'bg-dp-info text-white', 'label' => 'Confirmed'],
                \App\Enums\BookingStatus::InPreparation => ['color' => 'bg-primary text-white', 'label' => 'In Prep'],
                \App\Enums\BookingStatus::Completed => ['color' => 'bg-success text-white', 'label' => 'Completed'],
                \App\Enums\BookingStatus::Cancelled => ['color' => 'bg-base-content/40 text-white', 'label' => 'Cancelled'],
                default => ['color' => 'bg-base-200 text-base-content/60', 'label' => 'Unknown'],
            };
        @endphp

        <div class="px-4 py-2 rounded-full {{ $statusConfig['color'] }} text-[11px] font-black uppercase tracking-widest">
            {{ $statusConfig['label'] }}
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Event Details -->
            <div class="bg-white border border-base-content/10 rounded-2xl p-6 shadow-sm">
                <h2 class="text-[11px] font-bold text-primary uppercase tracking-widest mb-5">Event Details</h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
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
                        <div class="text-[11px] font-bold text-base-content/40 uppercase tracking-wide mb-1">Event Type</div>
                        <div class="text-[15px] font-semibold text-base-content">
                            @if($booking->event_type)
                                {{ $booking->event_type === \App\Enums\EventType::Other ? $booking->event_type_other : $booking->event_type->name }}
                            @else
                                Not specified
                            @endif
                        </div>
                    </div>
                    <div>
                        <div class="text-[11px] font-bold text-base-content/40 uppercase tracking-wide mb-1">Booked On</div>
                        <div class="text-[15px] font-semibold text-base-content">
                            {{ $booking->created_at->format('M j, Y \a\t g:i A') }}
                        </div>
                    </div>
                </div>
            </div>

            <!-- Packages Ordered -->
            <div class="bg-white border border-base-content/10 rounded-2xl shadow-sm overflow-hidden">
                <div class="p-6 border-b border-base-content/10">
                    <h2 class="text-[11px] font-bold text-primary uppercase tracking-widest">Packages Ordered</h2>
                </div>
                <div class="divide-y divide-base-content/10">
                    @foreach($booking->items as $item)
                        <div class="px-6 py-4 flex items-center justify-between gap-4">
                            <div class="flex-1 min-w-0">
                                <div class="text-[14px] font-semibold text-base-content truncate">{{ $item->package_name }}</div>
                                @if($item->package_description)
                                    <div class="text-[12px] text-base-content/50 font-medium mt-0.5 truncate">{{ Str::limit($item->package_description, 80) }}</div>
                                @endif
                            </div>
                            <div class="text-right shrink-0">
                                <div class="text-[13px] font-bold text-base-content">GH&#8373; {{ number_format($item->price * $item->quantity, 2) }}</div>
                                <div class="text-[11px] text-base-content/50 font-medium">{{ $item->quantity }} x GH&#8373; {{ number_format($item->price, 2) }}</div>
                            </div>
                        </div>
                    @endforeach
                </div>
                <div class="px-6 py-4 bg-base-200/30 border-t border-base-content/10 flex items-center justify-between">
                    <span class="text-[13px] font-bold text-base-content/60 uppercase tracking-wide">Total</span>
                    <span class="text-lg font-bold text-base-content">GH&#8373; {{ number_format($booking->total_amount, 2) }}</span>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Payment Status -->
            <div class="bg-white border border-base-content/10 rounded-2xl p-6 shadow-sm">
                <h2 class="text-[11px] font-bold text-primary uppercase tracking-widest mb-5">Payment</h2>

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

                    @if($booking->payment)
                        <div class="flex items-center justify-between">
                            <span class="text-[12px] font-medium text-base-content/50">Method</span>
                            <span class="text-[13px] font-semibold text-base-content">{{ $booking->payment->method?->name ?? '-' }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-[12px] font-medium text-base-content/50">Amount</span>
                            <span class="text-[13px] font-semibold text-base-content">GH&#8373; {{ number_format($booking->payment->amount, 2) }}</span>
                        </div>
                        @if($booking->payment->paid_at)
                            <div class="flex items-center justify-between">
                                <span class="text-[12px] font-medium text-base-content/50">Paid On</span>
                                <span class="text-[13px] font-semibold text-base-content">{{ \Carbon\Carbon::parse($booking->payment->paid_at)->format('M j, Y') }}</span>
                            </div>
                        @endif
                    @endif

                    @if($booking->payment_channel)
                        <div class="flex items-center justify-between">
                            <span class="text-[12px] font-medium text-base-content/50">Channel</span>
                            <span class="text-[13px] font-semibold text-base-content">
                                @if($booking->payment_channel === '13') MTN MoMo
                                @elseif($booking->payment_channel === '6') Telecel
                                @elseif($booking->payment_channel === '7') AT Money
                                @else {{ $booking->payment_channel }}
                                @endif
                            </span>
                        </div>
                    @endif

                    @if($booking->payer_number)
                        <div class="flex items-center justify-between">
                            <span class="text-[12px] font-medium text-base-content/50">Paid Via</span>
                            <span class="text-[13px] font-semibold text-base-content">{{ $booking->payer_number }}</span>
                        </div>
                    @endif

                    @if($booking->payment_reference)
                        <div class="flex flex-col gap-1 mt-2">
                            <span class="text-[12px] font-medium text-base-content/50">Transaction Reference</span>
                            <span class="text-[11px] font-mono font-semibold text-base-content break-all bg-base-200/50 p-1.5 rounded">{{ $booking->payment_reference }}</span>
                        </div>
                    @endif
                </div>

                <!-- Actions -->
                <div class="mt-6 space-y-3">
                    @if($booking->payment_status === \App\Enums\PaymentStatus::Unpaid)
                        <a href="{{ route('booking.payment', $booking->reference) }}" class="flex items-center justify-center gap-2 w-full px-4 py-3 bg-primary text-white rounded-xl font-semibold text-[13px] hover:bg-primary/90 transition-colors shadow-sm">
                            <svg xmlns="http://www.w3.org/2000/svg" class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                            Pay Now
                        </a>
                    @endif

                    @if($booking->payment_status === \App\Enums\PaymentStatus::Paid)
                        <a href="{!! app(\App\Services\InvoiceService::class)->getDownloadUrl($booking) !!}" target="_blank" class="flex items-center justify-center gap-2 w-full px-4 py-3 bg-base-200 text-base-content rounded-xl font-semibold text-[13px] hover:bg-base-200/80 transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            Download Invoice
                        </a>
                    @endif
                </div>
            </div>

            <!-- Customer Info -->
            <div class="bg-white border border-base-content/10 rounded-2xl p-6 shadow-sm">
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
