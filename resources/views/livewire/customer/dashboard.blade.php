<div>
    {{-- Welcome Header --}}
    <div class="mb-8 sm:mb-10 flex flex-col sm:flex-row sm:items-end justify-between gap-4 sm:gap-6">
        <div>
            <h1 class="text-2xl sm:text-3xl font-semibold text-base-content mb-2">Welcome back, {{ auth()->user()->displayName() }}</h1>
            <p class="text-base-content/60 text-[14px] sm:text-[15px] font-medium">Here's an overview of your catering bookings and activity.</p>
        </div>
        <div class="flex items-center gap-4">
            <div class="px-4 py-2 bg-white rounded-full border border-base-content/10 shadow-sm flex items-center gap-3">
                <div class="size-2 rounded-full bg-success animate-pulse"></div>
                <span class="text-[11px] font-bold uppercase tracking-widest text-base-content/60">Account Active</span>
            </div>
        </div>
    </div>

    {{-- Stat Cards --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-5 mb-8 sm:mb-10">
        <div class="bg-white border border-base-content/10 rounded-2xl p-4 sm:p-6 shadow-sm">
            <div class="flex items-center justify-between mb-3 sm:mb-4">
                <div class="size-10 bg-primary/10 rounded-xl flex items-center justify-center text-primary">
                    <svg xmlns="http://www.w3.org/2000/svg" class="size-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                </div>
            </div>
            <div class="text-xl sm:text-2xl font-bold text-base-content mb-1">{{ $totalBookings }}</div>
            <div class="text-[11px] sm:text-[12px] font-medium text-base-content/50 uppercase tracking-wide">Total Bookings</div>
        </div>
        <div class="bg-white border border-base-content/10 rounded-2xl p-4 sm:p-6 shadow-sm">
            <div class="flex items-center justify-between mb-3 sm:mb-4">
                <div class="size-10 bg-dp-info/10 rounded-xl flex items-center justify-center text-dp-info">
                    <svg xmlns="http://www.w3.org/2000/svg" class="size-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>
            <div class="text-xl sm:text-2xl font-bold text-base-content mb-1">{{ $upcomingBookings }}</div>
            <div class="text-[11px] sm:text-[12px] font-medium text-base-content/50 uppercase tracking-wide">Upcoming</div>
        </div>
        <div class="bg-white border border-base-content/10 rounded-2xl p-4 sm:p-6 shadow-sm">
            <div class="flex items-center justify-between mb-3 sm:mb-4">
                <div class="size-10 bg-success/10 rounded-xl flex items-center justify-center text-success">
                    <svg xmlns="http://www.w3.org/2000/svg" class="size-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                </div>
            </div>
            <div class="text-xl sm:text-2xl font-bold text-base-content mb-1">GH&#8373; {{ number_format($totalSpent, 2) }}</div>
            <div class="text-[11px] sm:text-[12px] font-medium text-base-content/50 uppercase tracking-wide">Total Spent</div>
        </div>
        <div class="bg-white border border-base-content/10 rounded-2xl p-4 sm:p-6 shadow-sm">
            <div class="flex items-center justify-between mb-3 sm:mb-4">
                <div class="size-10 bg-dp-warning/10 rounded-xl flex items-center justify-center text-dp-warning">
                    <svg xmlns="http://www.w3.org/2000/svg" class="size-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                </div>
            </div>
            <div class="text-xl sm:text-2xl font-bold text-base-content mb-1">{{ $pendingPayments }}</div>
            <div class="text-[11px] sm:text-[12px] font-medium text-base-content/50 uppercase tracking-wide">Pending Payments</div>
        </div>
    </div>

    {{-- Recent Meal Orders --}}
    <div class="flex items-center justify-between mb-4 sm:mb-6">
        <h2 class="text-lg sm:text-xl font-semibold text-base-content">Recent Meal Orders</h2>
        @if($recentMeals->isNotEmpty())
            <a href="{{ route('dashboard.meals.index') }}" wire:navigate class="text-[13px] font-semibold text-primary hover:text-primary/80 transition-colors">
                View All &rarr;
            </a>
        @endif
    </div>

    @if($recentMeals->isEmpty())
        <div class="bg-white border border-base-content/10 rounded-2xl p-8 sm:p-10 text-center shadow-sm mb-8 sm:mb-10">
            <div class="size-16 sm:size-20 bg-base-200 rounded-full flex items-center justify-center mx-auto mb-5 text-primary/20">
                @include('layouts.partials.icons.cake', ['class' => 'size-8 sm:size-10'])
            </div>
            <h3 class="text-lg sm:text-xl font-semibold text-base-content mb-3">No meal orders yet</h3>
            <p class="text-base-content/60 text-[14px] max-w-md mx-auto mb-6 font-medium">Browse our premium catering packages to place your first order.</p>
            <a href="{{ route('packages.browse') }}" wire:navigate class="btn btn-primary btn-sm rounded-xl">Browse Packages</a>
        </div>
    @else
        <div class="space-y-3 mb-8 sm:mb-10">
            @foreach($recentMeals as $booking)
                <a href="{{ route('dashboard.meals.show', $booking->reference) }}" wire:navigate wire:key="meal-{{ $booking->id }}" class="bg-white border border-base-content/10 rounded-xl p-4 sm:p-5 shadow-sm hover:shadow-md transition-all block group">
                    <div class="flex items-start sm:items-center justify-between gap-3 mb-3">
                        <div class="flex items-center gap-2 sm:gap-3 min-w-0">
                            <span class="font-mono text-[12px] sm:text-[13px] font-bold text-base-content bg-base-200 px-2 py-0.5 rounded-lg shrink-0">{{ $booking->reference }}</span>
                            <span class="text-[12px] text-base-content/40 font-medium hidden sm:inline">{{ $booking->created_at->format('M j, Y') }}</span>
                        </div>
                        @php
                            $statusConfig = match($booking->status) {
                                \App\Enums\BookingStatus::Pending => ['color' => 'bg-[#FFC926] text-black', 'label' => 'Pending'],
                                \App\Enums\BookingStatus::Confirmed => ['color' => 'bg-[#18542A] text-white', 'label' => 'Confirmed'],
                                \App\Enums\BookingStatus::InPreparation => ['color' => 'bg-[#9ABC05] text-white', 'label' => 'In Prep'],
                                \App\Enums\BookingStatus::Completed => ['color' => 'bg-base-content/20 text-base-content/60', 'label' => 'Completed'],
                                \App\Enums\BookingStatus::Cancelled => ['color' => 'bg-[#D52518] text-white', 'label' => 'Cancelled'],
                                default => ['color' => 'bg-base-200 text-base-content/60', 'label' => 'Unknown'],
                            };
                        @endphp
                        <span class="px-2 py-0.5 rounded-full {{ $statusConfig['color'] }} text-[9px] sm:text-[10px] font-black uppercase tracking-widest shrink-0">
                            {{ $statusConfig['label'] }}
                        </span>
                    </div>
                    <div class="flex items-center justify-between gap-4">
                        <div class="flex items-center gap-3 sm:gap-4 text-[12px] sm:text-[13px] text-base-content/60 font-medium min-w-0">
                            <span class="truncate">
                                @if($booking->items->isNotEmpty())
                                    {{ $booking->items->first()->package_name }}
                                    @if($booking->items->count() > 1)
                                        <span class="text-primary font-bold">+{{ $booking->items->count() - 1 }}</span>
                                    @endif
                                @endif
                            </span>
                            <span class="text-base-content/20 hidden sm:inline">&bull;</span>
                            <span class="hidden sm:inline">{{ $booking->pax ?? '--' }} guests</span>
                            <span class="text-base-content/20 hidden sm:inline">&bull;</span>
                            <span class="hidden sm:inline">{{ $booking->is_buffet ? 'Buffet' : 'Plates' }}</span>
                        </div>
                        <span class="text-[14px] font-bold text-base-content shrink-0">GH&#8373; {{ number_format($booking->total_amount, 2) }}</span>
                    </div>
                </a>
            @endforeach
        </div>
    @endif

    {{-- Recent Events --}}
    <div class="flex items-center justify-between mb-4 sm:mb-6">
        <h2 class="text-lg sm:text-xl font-semibold text-base-content">Recent Events</h2>
        @if($recentEvents->isNotEmpty())
            <a href="{{ route('dashboard.events.index') }}" wire:navigate class="text-[13px] font-semibold text-primary hover:text-primary/80 transition-colors">
                View All &rarr;
            </a>
        @endif
    </div>

    @if($recentEvents->isEmpty())
        <div class="bg-white border border-base-content/10 rounded-2xl p-8 sm:p-10 text-center shadow-sm">
            <div class="size-16 sm:size-20 bg-base-200 rounded-full flex items-center justify-center mx-auto mb-5 text-primary/20">
                @include('layouts.partials.icons.clipboard-document-list', ['class' => 'size-8 sm:size-10'])
            </div>
            <h3 class="text-lg sm:text-xl font-semibold text-base-content mb-3">No events yet</h3>
            <p class="text-base-content/60 text-[14px] max-w-md mx-auto mb-6 font-medium">Let us cater your next event — weddings, birthdays, corporate events and more.</p>
            <a href="{{ route('event-booking') }}" wire:navigate class="btn btn-primary btn-sm rounded-xl">Book an Event</a>
        </div>
    @else
        <div class="space-y-3">
            @foreach($recentEvents as $booking)
                <a href="{{ route('dashboard.events.show', $booking->reference) }}" wire:navigate wire:key="event-{{ $booking->id }}" class="bg-white border border-base-content/10 rounded-xl p-4 sm:p-5 shadow-sm hover:shadow-md transition-all block group">
                    <div class="flex items-start sm:items-center justify-between gap-3 mb-3">
                        <div class="flex items-center gap-2 sm:gap-3 min-w-0">
                            <span class="font-mono text-[12px] sm:text-[13px] font-bold text-base-content bg-base-200 px-2 py-0.5 rounded-lg shrink-0">{{ $booking->reference }}</span>
                            @if($booking->event_type)
                                @php
                                    $eventTypeLabel = $booking->event_type === \App\Enums\EventType::Other
                                        ? ($booking->event_type_other ?: 'Other')
                                        : $booking->event_type->name;
                                @endphp
                                <x-badge :type="$booking->event_type->value">{{ $eventTypeLabel }}</x-badge>
                            @endif
                        </div>
                        @php
                            $statusConfig = match($booking->status) {
                                \App\Enums\BookingStatus::Pending => ['color' => 'bg-[#FFC926] text-black', 'label' => 'Pending'],
                                \App\Enums\BookingStatus::Confirmed => ['color' => 'bg-[#18542A] text-white', 'label' => 'Confirmed'],
                                \App\Enums\BookingStatus::Completed => ['color' => 'bg-base-content/20 text-base-content/60', 'label' => 'Completed'],
                                \App\Enums\BookingStatus::Cancelled => ['color' => 'bg-[#D52518] text-white', 'label' => 'Cancelled'],
                                default => ['color' => 'bg-base-200 text-base-content/60', 'label' => str($booking->status?->value ?? 'unknown')->title()],
                            };
                        @endphp
                        <span class="px-2 py-0.5 rounded-full {{ $statusConfig['color'] }} text-[9px] sm:text-[10px] font-black uppercase tracking-widest shrink-0">
                            {{ $statusConfig['label'] }}
                        </span>
                    </div>
                    <div class="flex items-center justify-between gap-4">
                        <div class="flex items-center gap-3 sm:gap-4 text-[12px] sm:text-[13px] text-base-content/60 font-medium">
                            <span>{{ $booking->event_date ? $booking->event_date->format('M j, Y') : 'Date TBD' }}</span>
                            @if($booking->event_start_time)
                                <span class="text-base-content/20">&bull;</span>
                                <span>{{ \Carbon\Carbon::parse($booking->event_start_time)->format('g:i A') }}</span>
                            @endif
                            <span class="text-base-content/20 hidden sm:inline">&bull;</span>
                            <span class="hidden sm:inline">{{ $booking->pax ?? '--' }} guests</span>
                        </div>
                        @if($booking->total_amount > 0)
                            <span class="text-[14px] font-bold text-base-content shrink-0">GH&#8373; {{ number_format($booking->total_amount, 2) }}</span>
                        @else
                            <span class="text-[12px] font-semibold text-base-content/40 italic shrink-0">Quote Pending</span>
                        @endif
                    </div>
                </a>
            @endforeach
        </div>
    @endif
</div>
