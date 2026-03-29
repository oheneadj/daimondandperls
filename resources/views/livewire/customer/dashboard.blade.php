<div>
    <!-- Welcome Header -->
    <div class="mb-10 flex flex-col md:flex-row md:items-end justify-between gap-6">
        <div>
            <h1 class="text-3xl font-semibold text-base-content mb-2">Welcome back, {{ auth()->user()->displayName() }}</h1>
            <p class="text-base-content/60 text-[15px] font-medium">Here's an overview of your catering bookings and activity.</p>
        </div>
        <div class="flex items-center gap-4">
            <div class="px-4 py-2 bg-white rounded-full border border-base-content/10 shadow-sm flex items-center gap-3">
                <div class="size-2 rounded-full bg-success animate-pulse"></div>
                <span class="text-[11px] font-bold uppercase tracking-widest text-base-content/60">Account Active</span>
            </div>
        </div>
    </div>

    <!-- Stat Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 mb-10">
        <!-- Total Bookings -->
        <div class="bg-white border border-base-content/10 rounded-2xl p-6 shadow-sm">
            <div class="flex items-center justify-between mb-4">
                <div class="size-10 bg-primary/10 rounded-xl flex items-center justify-center text-primary">
                    <svg xmlns="http://www.w3.org/2000/svg" class="size-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                </div>
            </div>
            <div class="text-2xl font-bold text-base-content mb-1">{{ $totalBookings }}</div>
            <div class="text-[12px] font-medium text-base-content/50 uppercase tracking-wide">Total Bookings</div>
        </div>

        <!-- Upcoming Events -->
        <div class="bg-white border border-base-content/10 rounded-2xl p-6 shadow-sm">
            <div class="flex items-center justify-between mb-4">
                <div class="size-10 bg-dp-info/10 rounded-xl flex items-center justify-center text-dp-info">
                    <svg xmlns="http://www.w3.org/2000/svg" class="size-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>
            <div class="text-2xl font-bold text-base-content mb-1">{{ $upcomingBookings }}</div>
            <div class="text-[12px] font-medium text-base-content/50 uppercase tracking-wide">Upcoming Events</div>
        </div>

        <!-- Total Spent -->
        <div class="bg-white border border-base-content/10 rounded-2xl p-6 shadow-sm">
            <div class="flex items-center justify-between mb-4">
                <div class="size-10 bg-success/10 rounded-xl flex items-center justify-center text-success">
                    <svg xmlns="http://www.w3.org/2000/svg" class="size-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                </div>
            </div>
            <div class="text-2xl font-bold text-base-content mb-1">GH&#8373; {{ number_format($totalSpent, 2) }}</div>
            <div class="text-[12px] font-medium text-base-content/50 uppercase tracking-wide">Total Spent</div>
        </div>

        <!-- Pending Payments -->
        <div class="bg-white border border-base-content/10 rounded-2xl p-6 shadow-sm">
            <div class="flex items-center justify-between mb-4">
                <div class="size-10 bg-dp-warning/10 rounded-xl flex items-center justify-center text-dp-warning">
                    <svg xmlns="http://www.w3.org/2000/svg" class="size-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                </div>
            </div>
            <div class="text-2xl font-bold text-base-content mb-1">{{ $pendingPayments }}</div>
            <div class="text-[12px] font-medium text-base-content/50 uppercase tracking-wide">Pending Payments</div>
        </div>
    </div>

    <!-- Recent Bookings -->
    <div class="flex items-center justify-between mb-6">
        <h2 class="text-xl font-semibold text-base-content">Recent Bookings</h2>
        @if($recentBookings->isNotEmpty())
            <a href="{{ route('dashboard.bookings.index') }}" wire:navigate class="text-[13px] font-semibold text-primary hover:text-primary/80 transition-colors">
                View All &rarr;
            </a>
        @endif
    </div>

    @if($recentBookings->isEmpty())
        <div class="bg-white border border-base-content/10 rounded-2xl p-12 lg:p-20 text-center shadow-sm">
            <div class="size-24 bg-base-200 rounded-full flex items-center justify-center mx-auto mb-8 text-primary/20">
                <svg xmlns="http://www.w3.org/2000/svg" class="size-12" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                </svg>
            </div>
            <h3 class="text-2xl font-semibold text-base-content mb-4">No bookings yet</h3>
            <p class="text-base-content/60 text-[15px] max-w-lg mx-auto mb-10 font-medium">You haven't made any bookings yet. Browse our premium catering packages to get started.</p>
            <a href="{{ route('packages.browse') }}" wire:navigate class="inline-flex items-center gap-2 px-6 py-3 bg-primary text-white rounded-xl font-semibold text-[14px] hover:bg-primary/90 transition-colors shadow-sm">
                Browse Packages
            </a>
        </div>
    @else
        <div class="grid gap-5">
            @foreach($recentBookings as $booking)
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

                        <!-- Arrow indicator -->
                        <div class="hidden lg:flex items-center px-6 text-base-content/20 group-hover:text-primary transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" class="size-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                            </svg>
                        </div>
                    </div>
                </a>
            @endforeach
        </div>
    @endif
</div>
