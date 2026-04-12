<div class="space-y-6">
    {{-- Page Header --}}
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-6">
        <div>
            <h1 class="text-[28px] font-semibold text-base-content leading-tight">
                {{ __('Event Bookings') }}
            </h1>
            <p class="text-[14px] text-base-content/50 mt-1">{{ __('Manage all event inquiries and catering events') }}</p>
        </div>
    </div>

    {{-- Stats Bar --}}
    <div class="grid grid-cols-2 md:grid-cols-5 gap-4">
        <div class="bg-white border border-base-content/5 rounded-xl p-4 flex items-center gap-4">
            <div class="w-10 h-10 rounded-xl bg-[#F96015]/10 flex items-center justify-center">
                @include('layouts.partials.icons.clipboard-document-list', ['class' => 'w-5 h-5 text-[#F96015]'])
            </div>
            <div>
                <p class="text-[20px] font-bold text-base-content">{{ number_format($counts['total']) }}</p>
                <p class="text-[10px] font-bold uppercase tracking-widest text-base-content/40">{{ __('Total') }}</p>
            </div>
        </div>
        <div class="bg-white border border-base-content/5 rounded-xl p-4 flex items-center gap-4">
            <div class="w-10 h-10 rounded-xl bg-[#FFC926]/10 flex items-center justify-center">
                @include('layouts.partials.icons.information-circle-solid', ['class' => 'w-5 h-5 text-[#FFC926]'])
            </div>
            <div>
                <p class="text-[20px] font-bold text-base-content">{{ number_format($counts['pending']) }}</p>
                <p class="text-[10px] font-bold uppercase tracking-widest text-base-content/40">{{ __('Pending') }}</p>
            </div>
        </div>
        <div class="bg-white border border-base-content/5 rounded-xl p-4 flex items-center gap-4">
            <div class="w-10 h-10 rounded-xl bg-[#9ABC05]/10 flex items-center justify-center">
                @include('layouts.partials.icons.check-circle-solid', ['class' => 'w-5 h-5 text-[#9ABC05]'])
            </div>
            <div>
                <p class="text-[20px] font-bold text-base-content">{{ number_format($counts['confirmed']) }}</p>
                <p class="text-[10px] font-bold uppercase tracking-widest text-base-content/40">{{ __('Confirmed') }}</p>
            </div>
        </div>
        <div class="bg-white border border-base-content/5 rounded-xl p-4 flex items-center gap-4">
            <div class="w-10 h-10 rounded-xl bg-[#D52518]/10 flex items-center justify-center">
                @include('layouts.partials.icons.exclamation-triangle-solid', ['class' => 'w-5 h-5 text-[#D52518]'])
            </div>
            <div>
                <p class="text-[20px] font-bold text-base-content">{{ number_format($counts['unpaid']) }}</p>
                <p class="text-[10px] font-bold uppercase tracking-widest text-base-content/40">{{ __('Unpaid') }}</p>
            </div>
        </div>
        <div class="bg-white border border-[#A31C4E]/10 rounded-xl p-4 flex items-center gap-4">
            <div class="w-10 h-10 rounded-xl bg-[#A31C4E]/10 flex items-center justify-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-[#A31C4E]" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
            </div>
            <div>
                <p class="text-[20px] font-bold text-[#A31C4E]">{{ number_format($counts['upcoming']) }}</p>
                <p class="text-[10px] font-bold uppercase tracking-widest text-base-content/40">{{ __('Upcoming 30d') }}</p>
            </div>
        </div>
    </div>

    {{-- Table --}}
    <x-ui.table search="search">
        <x-slot name="filters">
            <div class="flex flex-wrap items-center gap-3">
                <select wire:model.live="status" class="bg-base-200 border-none text-[13px] rounded-lg px-3 py-2 focus:ring-2 focus:ring-[#F96015]/30 outline-none transition-all font-medium">
                    <option value="">All Statuses</option>
                    @foreach($statuses as $s)
                        <option wire:key="status-{{ $s->value }}" value="{{ $s->value }}">{{ str($s->value)->title()->replace('_', ' ') }}</option>
                    @endforeach
                </select>

                <select wire:model.live="paymentStatus" class="bg-base-200 border-none text-[13px] rounded-lg px-3 py-2 focus:ring-2 focus:ring-[#F96015]/30 outline-none transition-all font-medium">
                    <option value="">All Payments</option>
                    @foreach($paymentStatuses as $ps)
                        <option wire:key="pay-status-{{ $ps->value }}" value="{{ $ps->value }}">{{ str($ps->value)->title() }}</option>
                    @endforeach
                </select>

                <select wire:model.live="eventType" class="bg-base-200 border-none text-[13px] rounded-lg px-3 py-2 focus:ring-2 focus:ring-[#F96015]/30 outline-none transition-all font-medium">
                    <option value="">All Event Types</option>
                    @foreach($eventTypes as $et)
                        <option wire:key="event-type-{{ $et->value }}" value="{{ $et->value }}">{{ str($et->value)->title()->replace('_', ' ') }}</option>
                    @endforeach
                </select>

                <div class="flex items-center gap-2">
                    <input type="date" wire:model.live="startDate" class="bg-base-200 border-none text-[13px] rounded-lg px-3 py-2 focus:ring-2 focus:ring-[#F96015]/30 outline-none transition-all font-medium">
                    <span class="text-base-content/30 text-[11px] font-bold">&rarr;</span>
                    <input type="date" wire:model.live="endDate" class="bg-base-200 border-none text-[13px] rounded-lg px-3 py-2 focus:ring-2 focus:ring-[#F96015]/30 outline-none transition-all font-medium">
                </div>
            </div>
        </x-slot>

        <x-slot name="header">
            <x-ui.table.th>Ref #</x-ui.table.th>
            <x-ui.table.th>Customer</x-ui.table.th>
            <x-ui.table.th>Event Type</x-ui.table.th>
            <x-ui.table.th>Event Date</x-ui.table.th>
            <x-ui.table.th align="center">Pax</x-ui.table.th>
            <x-ui.table.th align="center">Payment</x-ui.table.th>
            <x-ui.table.th align="center">Status</x-ui.table.th>
            <x-ui.table.th align="right">Actions</x-ui.table.th>
        </x-slot>

        @forelse($bookings as $booking)
            @php
                $daysUntil = $booking->event_date
                    ? (int) now()->startOfDay()->diffInDays($booking->event_date->copy()->startOfDay(), false)
                    : null;
                $isUrgent = $daysUntil !== null
                    && $daysUntil >= 0
                    && $daysUntil <= 7
                    && ! in_array($booking->status?->value, ['completed', 'cancelled']);
            @endphp
            <x-ui.table.row wire:key="booking-{{ $booking->id }}" @class(['bg-[#FFF8F0]' => $isUrgent])>
                <x-ui.table.td>
                    <a href="{{ route('admin.bookings.show', $booking) }}" wire:navigate>
                        <span class="inline-flex items-center px-2.5 py-1 rounded-lg bg-base-200 text-base-content/70 text-[11px] font-bold tracking-wide hover:bg-base-300 transition-colors">
                            {{ $booking->reference }}
                        </span>
                    </a>
                </x-ui.table.td>
                <x-ui.table.td>
                    <div class="flex flex-col min-w-0">
                        <span class="text-[13px] font-semibold text-base-content truncate hover:text-[#F96015] transition-colors cursor-pointer">{{ $booking->customer->name }}</span>
                        <span class="text-[11px] text-base-content/40">{{ $booking->customer->phone }}</span>
                    </div>
                </x-ui.table.td>
                <x-ui.table.td>
                    <x-badge type="ghost" class="text-[11px]">{{ $booking->event_type?->value ? str($booking->event_type->value)->title()->replace('_', ' ') : 'N/A' }}</x-badge>
                </x-ui.table.td>
                <x-ui.table.td>
                    <div class="flex flex-col gap-0.5">
                        <div class="flex items-center gap-2">
                            <div class="w-6 h-6 rounded-md bg-base-200 flex items-center justify-center shrink-0">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 text-base-content/40" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            </div>
                            <span class="text-[13px] text-base-content font-medium">
                                {{ $booking->event_date?->format('d M, Y') ?? 'No Date' }}
                            </span>
                        </div>
                        @if($daysUntil !== null)
                            @if($daysUntil === 0)
                                <span class="text-[10px] font-bold text-[#F96015] uppercase tracking-wide ml-8">Today</span>
                            @elseif($daysUntil > 0)
                                <span class="text-[10px] font-bold text-[#9ABC05] uppercase tracking-wide ml-8">In {{ $daysUntil }}d</span>
                            @else
                                <span class="text-[10px] font-medium text-base-content/30 uppercase tracking-wide ml-8">Past</span>
                            @endif
                        @endif
                    </div>
                </x-ui.table.td>
                <x-ui.table.td align="center">
                    <span class="text-[13px] font-bold text-base-content">
                        {{ $booking->pax ?? '--' }}
                    </span>
                    <span class="text-[10px] text-base-content/40 block">
                        {{ $booking->is_buffet ? 'Buffet' : 'Plates' }}
                    </span>
                </x-ui.table.td>
                <x-ui.table.td align="center">
                    @php
                        $paymentColor = match($booking->payment_status?->value) {
                            'paid' => 'text-success',
                            'pending' => 'text-warning',
                            'unpaid' => 'text-error',
                            default => 'text-base-content/40'
                        };
                    @endphp
                    <div class="inline-flex items-center gap-1.5 {{ $paymentColor }} text-[11px] font-bold uppercase tracking-wide">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-3 h-3 opacity-50" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        {{ str($booking->payment_status?->value ?? 'unpaid')->replace('_', ' ')->title() }}
                    </div>
                    @if($booking->total_amount == 0 && in_array($booking->status?->value, ['pending', 'confirmed']))
                        <span class="block text-[10px] font-bold text-[#FFC926] uppercase tracking-wide mt-0.5">Quote Needed</span>
                    @endif
                </x-ui.table.td>
                <x-ui.table.td align="center">
                    <x-badge :type="$booking->status?->value ?? 'pending'" dot>
                        {{ str($booking->status?->value ?? 'pending')->replace('_', ' ')->title() }}
                    </x-badge>
                </x-ui.table.td>
                <x-ui.table.td align="right">
                    <div class="flex items-center justify-end gap-2">
                        <a href="{{ route('admin.bookings.show', $booking) }}" wire:navigate class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-[#18542A]/10 text-[#18542A] text-[12px] font-bold hover:bg-[#18542A]/20 transition-colors">
                            @include('layouts.partials.icons.eye', ['class' => 'w-3.5 h-3.5'])
                            View
                        </a>

                        @php
                            $isTerminal = in_array($booking->status?->value, ['completed', 'cancelled']);
                        @endphp

                        @if(!$isTerminal)
                            <div x-data="{ open: false }" class="relative">
                                <button @click="open = !open" @click.away="open = false" class="inline-flex items-center gap-1 px-2 py-1.5 rounded-lg bg-base-200 text-base-content/40 text-[12px] font-bold hover:bg-base-300 transition-colors">
                                    @include('layouts.partials.icons.cog-6-tooth', ['class' => 'w-4 h-4'])
                                </button>

                                <div x-show="open"
                                    x-transition:enter="transition ease-out duration-100"
                                    x-transition:enter-start="opacity-0 scale-95"
                                    x-transition:enter-end="opacity-100 scale-100"
                                    x-transition:leave="transition ease-in duration-75"
                                    x-transition:leave-start="opacity-100 scale-100"
                                    x-transition:leave-end="opacity-0 scale-95"
                                    class="absolute right-0 top-full mt-1.5 w-56 bg-white border border-base-content/10 shadow-xl rounded-xl py-2 z-20"
                                    style="display: none;">

                                    @if($booking->status?->value === 'pending')
                                        <button wire:confirm="Confirm this booking?" wire:click="confirmBooking({{ $booking->id }})" @click="open = false" class="w-full text-left px-4 py-2 hover:bg-[#9ABC05]/10 font-medium flex items-center gap-3 transition-colors">
                                            <div class="w-7 h-7 rounded-lg bg-[#9ABC05]/15 flex items-center justify-center shrink-0">
                                                @include('layouts.partials.icons.check-circle-solid', ['class' => 'w-4 h-4 text-[#9ABC05]'])
                                            </div>
                                            <div>
                                                <span class="text-[13px] text-base-content block font-bold">Verify & Confirm</span>
                                                <span class="text-[10px] text-base-content/40 font-medium">Approve initial request</span>
                                            </div>
                                        </button>
                                    @endif

                                    <div class="border-t border-base-content/5 my-1.5"></div>

                                    <button wire:confirm="Are you sure you want to cancel this booking?" wire:click="cancelBooking({{ $booking->id }})" @click="open = false" class="w-full text-left px-4 py-2 hover:bg-[#D52518]/10 font-medium flex items-center gap-3 transition-colors">
                                        <div class="w-7 h-7 rounded-lg bg-[#D52518]/15 flex items-center justify-center shrink-0">
                                            @include('layouts.partials.icons.exclamation-triangle-solid', ['class' => 'w-4 h-4 text-[#D52518]'])
                                        </div>
                                        <div>
                                            <span class="text-[13px] text-[#D52518] block font-bold">Terminate</span>
                                            <span class="text-[10px] text-base-content/40 font-medium">Cancel this event</span>
                                        </div>
                                    </button>
                                </div>
                            </div>
                        @else
                            <div class="w-8 h-8 flex items-center justify-center opacity-20 pointer-events-none">
                                @include('layouts.partials.icons.check-circle-solid', ['class' => 'w-4 h-4 text-base-content'])
                            </div>
                        @endif
                    </div>
                </x-ui.table.td>
            </x-ui.table.row>
        @empty
            <x-ui.table.empty colspan="8" />
        @endforelse

        <x-slot name="pagination">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div class="text-[12px] text-base-content/40 font-medium">
                    {{ __('Click on the pagination links to navigate through the event bookings') }}
                </div>
                <div class="flex items-center justify-end gap-2">
                    {{ $bookings?->links(data: ['scrollTo' => false]) }}
                </div>
            </div>
        </x-slot>
    </x-ui.table>
</div>
