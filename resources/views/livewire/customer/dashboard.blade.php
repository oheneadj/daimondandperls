<div class="bg-base-200 min-h-screen py-12 lg:py-20">
    <div class="container mx-auto px-4 lg:px-8 max-w-5xl">
        <!-- Header -->
        <div class="mb-12 flex flex-col md:flex-row md:items-end justify-between gap-6">
            <div>
                <h1 class=" text-4xl font-semibold text-base-content mb-2">My Bookings</h1>
                <p class="text-base-content/60 text-[15px] font-medium">Manage your catering orders and track implementation progress.</p>
            </div>
            <div class="flex items-center gap-4">
                <div class="px-4 py-2 bg-white rounded-full border border-base-content/10 shadow-sm flex items-center gap-3">
                    <div class="size-2 rounded-full bg-success animate-pulse"></div>
                    <span class="text-[11px] font-bold uppercase tracking-widest text-base-content/60">Account Active</span>
                </div>
            </div>
        </div>

        @if($bookings->isEmpty())
            <!-- Empty State -->
            <div class="bg-base-100 border border-base-content/10 rounded-[32px] p-12 lg:p-20 text-center shadow-sm">
                <div class="size-24 bg-base-200 rounded-full flex items-center justify-center mx-auto mb-8 text-primary/20">
                    <svg xmlns="http://www.w3.org/2000/svg" class="size-12" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                    </svg>
                </div>
                <h3 class=" text-2xl font-semibold text-base-content mb-4">No bookings found yet</h3>
                <p class="text-base-content/60 text-[15px] max-w-lg mx-auto mb-10 font-medium">You haven't made any bookings yet. Browse our premium catering packages to get started.</p>
                <x-ui.button href="{{ route('packages.browse') }}" variant="primary" size="lg">
                    Browse Packages
                </x-ui.button>
            </div>
        @else
            <!-- Bookings Grid -->
            <div class="grid gap-6">
                @foreach($bookings as $booking)
                    <div wire:key="booking-{{ $booking->id }}" class="bg-white border border-base-content/10 rounded-2xl overflow-hidden shadow-sm hover:shadow-md transition-all group">
                        <div class="flex flex-col lg:flex-row">
                            <!-- Status Badge & Ref (Left/Top) -->
                            <div class="px-6 py-6 lg:w-64 border-b lg:border-b-0 lg:border-r border-base-content/10 bg-base-200/30 flex flex-row lg:flex-col justify-between items-center lg:items-start gap-4">
                                <div>
                                    <div class="text-[10px] font-bold text-base-content/60 uppercase tracking-widest mb-1">Reference</div>
                                    <div class="font-mono text-[14px] font-bold text-base-content select-all">{{ $booking->reference }}</div>
                                </div>
                                
                                @php
                                    $statusConfig = match($booking->status) {
                                        \App\Enums\BookingStatus::Pending => ['color' => 'bg-dp-warning text-white', 'label' => 'Awaiting Review'],
                                        \App\Enums\BookingStatus::Confirmed => ['color' => 'bg-dp-info text-white', 'label' => 'Confirmed'],
                                        \App\Enums\BookingStatus::InPreparation => ['color' => 'bg-primary text-white', 'label' => 'In Prep'],
                                        \App\Enums\BookingStatus::Completed => ['color' => 'bg-success text-white', 'label' => 'Completed'],
                                        \App\Enums\BookingStatus::Cancelled => ['color' => 'bg-dp-text-muted text-white', 'label' => 'Cancelled'],
                                        default => ['color' => 'bg-base-200-mid text-base-content/60', 'label' => 'Unknown'],
                                    };
                                @endphp
                                
                                <div class="px-3 py-1.5 rounded-full {{ $statusConfig['color'] }} text-[10px] font-black uppercase tracking-widest">
                                    {{ $statusConfig['label'] }}
                                </div>
                            </div>

                            <!-- Details (Middle) -->
                            <div class="flex-1 p-6 flex flex-col justify-between gap-6">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                                    <div>
                                        <div class="text-[10px] font-bold text-primary uppercase tracking-widest mb-3">Event Details</div>
                                        <div class=" text-lg font-semibold text-base-content mb-1">
                                            {{ $booking->event_date ? $booking->event_date->format('l, F j, Y') : 'Date to be decided' }}
                                        </div>
                                        <div class="text-[13px] text-base-content/60 font-medium">
                                            @if($booking->event_type) 
                                                {{ $booking->event_type === \App\Enums\EventType::Other ? $booking->event_type_other : $booking->event_type->name }}
                                            @endif
                                            @if($booking->event_start_time) 
                                                {{ \Carbon\Carbon::parse($booking->event_start_time)->format('g:i A') }} start
                                            @endif
                                        </div>
                                    </div>

                                    <div>
                                        <div class="text-[10px] font-bold text-primary uppercase tracking-widest mb-3">Investment Summary</div>
                                        <div class=" text-xl font-bold text-base-content mb-1">GH₵ {{ number_format($booking->total_amount, 2) }}</div>
                                        <div class="flex items-center gap-2">
                                            @php
                                                $paymentStatusColor = match($booking->payment_status) {
                                                    \App\Enums\PaymentStatus::Paid => 'text-dp-success',
                                                    \App\Enums\PaymentStatus::Unpaid => 'text-error',
                                                    \App\Enums\PaymentStatus::Pending => 'text-dp-warning',
                                                    default => 'text-base-content/60',
                                                };
                                            @endphp
                                            <span class="text-[12px] font-bold {{ $paymentStatusColor }} uppercase tracking-wide">{{ $booking->payment_status->value }}</span>
                                            <span class="text-dp-border">•</span>
                                            <span class="text-[12px] text-base-content/60 font-medium">{{ $booking->items->count() }} Packages</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Actions (Right/Bottom) -->
                            <div class="p-6 lg:w-48 bg-base-200/10 border-t lg:border-t-0 lg:border-l border-base-content/10 flex items-center justify-center">
                                @if($booking->payment_status === \App\Enums\PaymentStatus::Unpaid)
                                    <x-ui.button href="{{ route('booking.payment', $booking->reference) }}" variant="primary" size="sm" class="w-full">
                                        Pay Now
                                    </x-ui.button>
                                @else
                                    <div class="flex flex-col gap-2 w-full">
                                        <x-ui.button href="{{ route('booking.payment', $booking->reference) }}" variant="ghost" size="sm" class="w-full">
                                            View Details
                                        </x-ui.button>
                                        @if($booking->payment_status === \App\Enums\PaymentStatus::Paid)
                                            <x-ui.button href="{{ app(\App\Services\InvoiceService::class)->getDownloadUrl($booking) }}" target="_blank" variant="secondary" size="sm" class="w-full">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="size-3.5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                                </svg>
                                                Invoice
                                            </x-ui.button>
                                        @endif
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>
