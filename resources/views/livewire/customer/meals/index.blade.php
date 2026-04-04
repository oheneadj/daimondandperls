<div>
    {{-- Header --}}
    <div class="mb-6 flex flex-col sm:flex-row sm:items-end justify-between gap-4">
        <div>
            <h1 class="text-2xl sm:text-[28px] font-semibold text-base-content leading-tight">My Meal Orders</h1>
            <p class="text-[14px] text-base-content/50 mt-1">Track and manage your catering meal orders.</p>
        </div>
        <a href="{{ route('packages.browse') }}" wire:navigate class="btn btn-primary btn-sm gap-2 shrink-0">
            <svg xmlns="http://www.w3.org/2000/svg" class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.5v15m7.5-7.5h-15" />
            </svg>
            New Order
        </a>
    </div>

    {{-- Filters --}}
    <div class="bg-white border border-base-content/10 rounded-xl p-3 sm:p-4 mb-6 shadow-sm">
        <div class="flex flex-col sm:flex-row gap-3">
            <div class="relative flex-1">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-base-content/40" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>
                <input type="text" wire:model.live.debounce.300ms="search" placeholder="Search by reference..."
                    class="w-full bg-base-200/50 border border-base-content/10 text-[13px] rounded-xl pl-9 pr-3 py-2.5 focus:ring-2 focus:ring-primary/20 focus:border-primary/30 transition-all outline-none">
            </div>
            <select wire:model.live="status" class="bg-base-200/50 border border-base-content/10 text-[13px] rounded-xl px-3 py-2.5 focus:ring-2 focus:ring-primary/20 focus:border-primary/30 transition-all outline-none">
                <option value="">All Statuses</option>
                @foreach(\App\Enums\BookingStatus::cases() as $s)
                    <option value="{{ $s->value }}">{{ str($s->value)->title()->replace('_', ' ') }}</option>
                @endforeach
            </select>
            <select wire:model.live="paymentStatus" class="bg-base-200/50 border border-base-content/10 text-[13px] rounded-xl px-3 py-2.5 focus:ring-2 focus:ring-primary/20 focus:border-primary/30 transition-all outline-none">
                <option value="">All Payments</option>
                @foreach(\App\Enums\PaymentStatus::cases() as $ps)
                    <option value="{{ $ps->value }}">{{ str($ps->value)->title() }}</option>
                @endforeach
            </select>
            @if($search || $status || $paymentStatus)
                <button wire:click="clearFilters" class="text-[12px] font-semibold text-base-content/50 hover:text-error px-3 py-2.5 transition-colors">
                    Clear
                </button>
            @endif
        </div>
    </div>

    {{-- Bookings List --}}
    @if($bookings->isEmpty())
        <div class="bg-white border border-base-content/10 rounded-2xl p-10 sm:p-12 text-center shadow-sm">
            <div class="size-20 bg-base-200 rounded-full flex items-center justify-center mx-auto mb-6 text-primary/20">
                @include('layouts.partials.icons.cake', ['class' => 'size-10'])
            </div>
            <h3 class="text-xl font-semibold text-base-content mb-3">No meal orders found</h3>
            <p class="text-base-content/60 text-[14px] max-w-md mx-auto mb-8 font-medium">
                @if($search || $status || $paymentStatus)
                    No orders match your filters. Try adjusting your search criteria.
                @else
                    You haven't placed any meal orders yet. Browse our packages to get started.
                @endif
            </p>
            @unless($search || $status || $paymentStatus)
                <a href="{{ route('packages.browse') }}" wire:navigate class="btn btn-primary btn-sm">Browse Packages</a>
            @endunless
        </div>
    @else
        <div class="space-y-4">
            @foreach($bookings as $booking)
                <div wire:key="meal-{{ $booking->id }}" class="bg-white border border-base-content/10 rounded-2xl shadow-sm overflow-hidden hover:shadow-md transition-all">
                    {{-- Top: Reference + Status --}}
                    <div class="px-4 sm:px-6 py-4 flex items-center justify-between border-b border-base-content/5 bg-base-200/20">
                        <div class="flex items-center gap-3">
                            <span class="font-mono text-[13px] font-bold text-base-content bg-base-200 px-2.5 py-1 rounded-lg">{{ $booking->reference }}</span>
                            <span class="hidden sm:inline text-[11px] text-base-content/40 font-medium">{{ $booking->created_at->format('M j, Y') }}</span>
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
                        <span class="px-2.5 py-1 rounded-full {{ $statusConfig['color'] }} text-[10px] font-black uppercase tracking-widest">
                            {{ $statusConfig['label'] }}
                        </span>
                    </div>

                    {{-- Body --}}
                    <div class="px-4 sm:px-6 py-4">
                        <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 mb-4">
                            {{-- Packages --}}
                            <div class="col-span-2">
                                <div class="text-[10px] font-bold text-base-content/40 uppercase tracking-widest mb-1">Packages</div>
                                <div class="text-[14px] font-semibold text-base-content truncate">
                                    @if($booking->items->isNotEmpty())
                                        {{ $booking->items->first()->package_name }}
                                        @if($booking->items->count() > 1)
                                            <span class="text-primary text-[12px] font-bold">+{{ $booking->items->count() - 1 }}</span>
                                        @endif
                                    @else
                                        N/A
                                    @endif
                                </div>
                            </div>
                            {{-- Pax & Style --}}
                            <div>
                                <div class="text-[10px] font-bold text-base-content/40 uppercase tracking-widest mb-1">Guests</div>
                                <div class="text-[14px] font-semibold text-base-content">{{ $booking->pax ?? '--' }}</div>
                            </div>
                            <div>
                                <div class="text-[10px] font-bold text-base-content/40 uppercase tracking-widest mb-1">Service</div>
                                <div class="text-[14px] font-semibold text-base-content">{{ $booking->is_buffet ? 'Buffet' : 'Plates' }}</div>
                            </div>
                        </div>

                        {{-- Amount + Payment + Actions --}}
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pt-3 border-t border-base-content/5">
                            <div class="flex items-center gap-4">
                                <span class="text-lg font-bold text-base-content">GH&#8373; {{ number_format($booking->total_amount, 2) }}</span>
                                @php
                                    $paymentColor = match($booking->payment_status) {
                                        \App\Enums\PaymentStatus::Paid => 'text-success',
                                        \App\Enums\PaymentStatus::Unpaid => 'text-error',
                                        \App\Enums\PaymentStatus::Pending => 'text-dp-warning',
                                        default => 'text-base-content/60',
                                    };
                                @endphp
                                <span class="text-[11px] font-bold {{ $paymentColor }} uppercase tracking-wide">{{ str($booking->payment_status->value)->title() }}</span>
                            </div>
                            <div class="flex items-center gap-2">
                                @if($booking->payment_status === \App\Enums\PaymentStatus::Unpaid)
                                    <a href="{{ route('booking.payment', $booking->reference) }}" class="inline-flex items-center gap-1.5 px-4 py-2.5 rounded-xl bg-primary text-white text-[12px] font-bold hover:bg-primary/90 transition-colors min-h-[44px]">
                                        Pay Now
                                    </a>
                                @elseif($booking->payment_status === \App\Enums\PaymentStatus::Paid)
                                    <a href="{!! app(\App\Services\InvoiceService::class)->getDownloadUrl($booking) !!}" target="_blank" class="inline-flex items-center gap-1.5 px-3 py-2.5 rounded-xl bg-base-200 text-base-content/70 text-[12px] font-bold hover:bg-base-300 transition-colors min-h-[44px]">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                                        Invoice
                                    </a>
                                @endif
                                <a href="{{ route('dashboard.meals.show', $booking->reference) }}" wire:navigate class="inline-flex items-center gap-1.5 px-3 py-2.5 rounded-xl bg-[#18542A]/10 text-[#18542A] text-[12px] font-bold hover:bg-[#18542A]/20 transition-colors min-h-[44px]">
                                    @include('layouts.partials.icons.eye', ['class' => 'w-3.5 h-3.5'])
                                    View
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="mt-8">
            {{ $bookings->links() }}
        </div>
    @endif
</div>
