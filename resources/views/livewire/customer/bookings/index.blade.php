<div>
    <!-- Header -->
    <div class="mb-8 flex flex-col md:flex-row md:items-end justify-between gap-6">
        <div>
            <h1 class="text-3xl font-semibold text-base-content mb-2">My Bookings</h1>
            <p class="text-base-content/60 text-[15px] font-medium">Track and manage all your catering orders.</p>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white border border-base-content/10 rounded-2xl p-4 mb-6 shadow-sm">
        <div class="flex flex-col sm:flex-row gap-3">
            <!-- Search -->
            <div class="relative flex-1">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-base-content/40" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>
                <input type="text" wire:model.live.debounce.300ms="search" placeholder="Search by reference..."
                    class="w-full bg-base-200/50 border border-base-content/10 text-[13px] rounded-xl pl-9 pr-3 py-2.5 focus:ring-2 focus:ring-primary/20 focus:border-primary/30 transition-all outline-none">
            </div>

            <!-- Status Filter -->
            <select wire:model.live="status" class="bg-base-200/50 border border-base-content/10 text-[13px] rounded-xl px-3 py-2.5 focus:ring-2 focus:ring-primary/20 focus:border-primary/30 transition-all outline-none min-w-[150px]">
                <option value="">All Statuses</option>
                @foreach(\App\Enums\BookingStatus::cases() as $s)
                    <option value="{{ $s->value }}">{{ $s->name }}</option>
                @endforeach
            </select>

            <!-- Payment Status Filter -->
            <select wire:model.live="paymentStatus" class="bg-base-200/50 border border-base-content/10 text-[13px] rounded-xl px-3 py-2.5 focus:ring-2 focus:ring-primary/20 focus:border-primary/30 transition-all outline-none min-w-[150px]">
                <option value="">All Payments</option>
                @foreach(\App\Enums\PaymentStatus::cases() as $ps)
                    <option value="{{ $ps->value }}">{{ $ps->name }}</option>
                @endforeach
            </select>

            @if($search || $status || $paymentStatus)
                <button wire:click="clearFilters" class="text-[12px] font-semibold text-base-content/50 hover:text-error px-3 py-2.5 transition-colors">
                    Clear
                </button>
            @endif
        </div>
    </div>

    <!-- Bookings List -->
    @if($bookings->isEmpty())
        <div class="bg-white border border-base-content/10 rounded-2xl p-12 text-center shadow-sm">
            <div class="size-20 bg-base-200 rounded-full flex items-center justify-center mx-auto mb-6 text-primary/20">
                <svg xmlns="http://www.w3.org/2000/svg" class="size-10" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
            </div>
            <h3 class="text-xl font-semibold text-base-content mb-3">No bookings found</h3>
            <p class="text-base-content/60 text-[14px] max-w-md mx-auto mb-8 font-medium">
                @if($search || $status || $paymentStatus)
                    No bookings match your filters. Try adjusting your search criteria.
                @else
                    You haven't made any bookings yet. Browse our packages to get started.
                @endif
            </p>
            @unless($search || $status || $paymentStatus)
                <a href="{{ route('packages.browse') }}" wire:navigate class="inline-flex items-center gap-2 px-6 py-3 bg-primary text-white rounded-xl font-semibold text-[14px] hover:bg-primary/90 transition-colors shadow-sm">
                    Browse Packages
                </a>
            @endunless
        </div>
    @else
        <div class="grid gap-5">
            @foreach($bookings as $booking)
                <a href="{{ route('dashboard.bookings.show', $booking->reference) }}" wire:navigate wire:key="booking-{{ $booking->id }}" class="bg-white border border-base-content/10 rounded-2xl overflow-hidden shadow-sm hover:shadow-md transition-all group block">
                    <div class="flex flex-col lg:flex-row">
                        <!-- Status Badge & Ref -->
                        <div class="px-6 py-5 lg:w-56 border-b lg:border-b-0 lg:border-r border-base-content/10 bg-base-200/30 flex flex-row lg:flex-col justify-between items-center lg:items-start gap-4">
                            <div>
                                <div class="text-[10px] font-bold text-base-content/60 uppercase tracking-widest mb-1">Reference</div>
                                <div class="font-mono text-[14px] font-bold text-base-content">{{ $booking->reference }}</div>
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

                            <div class="px-3 py-1.5 rounded-full {{ $statusConfig['color'] }} text-[10px] font-black uppercase tracking-widest">
                                {{ $statusConfig['label'] }}
                            </div>
                        </div>

                        <!-- Details -->
                        <div class="flex-1 p-6 flex flex-col justify-between gap-4">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <div class="text-[10px] font-bold text-primary uppercase tracking-widest mb-2">Event Details</div>
                                    <div class="text-[15px] font-semibold text-base-content mb-1">
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
                                    <div class="text-[10px] font-bold text-primary uppercase tracking-widest mb-2">Investment</div>
                                    <div class="text-xl font-bold text-base-content mb-1">GH&#8373; {{ number_format($booking->total_amount, 2) }}</div>
                                    <div class="flex items-center gap-2">
                                        @php
                                            $paymentColor = match($booking->payment_status) {
                                                \App\Enums\PaymentStatus::Paid => 'text-success',
                                                \App\Enums\PaymentStatus::Unpaid => 'text-error',
                                                \App\Enums\PaymentStatus::Pending => 'text-dp-warning',
                                                default => 'text-base-content/60',
                                            };
                                        @endphp
                                        <span class="text-[12px] font-bold {{ $paymentColor }} uppercase tracking-wide">{{ $booking->payment_status->value }}</span>
                                        <span class="text-base-content/20">&bull;</span>
                                        <span class="text-[12px] text-base-content/60 font-medium">{{ $booking->items->count() }} Packages</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Arrow -->
                        <div class="hidden lg:flex items-center px-6 text-base-content/20 group-hover:text-primary transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" class="size-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                            </svg>
                        </div>
                    </div>
                </a>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="mt-8">
            {{ $bookings->links() }}
        </div>
    @endif
</div>
