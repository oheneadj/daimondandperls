@props(['booking'])

<div class="space-y-6 sm:space-y-10 pb-10">
    {{-- Top Bar: Breadcrumbs + Status Stepper --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-4 sm:mb-8">
        {{-- Breadcrumbs --}}
        <div class="flex items-center gap-2 text-[11px] font-bold uppercase tracking-widest text-base-content/60">
            @if($booking->booking_type === \App\Enums\BookingType::Event)
                <a href="{{ route('admin.events.index') }}" wire:navigate class="hover:text-[#F96015] transition-colors">{{ __('Events') }}</a>
            @else
                <a href="{{ route('admin.bookings.index') }}" wire:navigate class="hover:text-[#F96015] transition-colors">{{ __('Bookings') }}</a>
            @endif
            <svg xmlns="http://www.w3.org/2000/svg" class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
            </svg>
            <span class="text-base-content">{{ $booking->reference }}</span>
        </div>

        @php
            $flowSteps = ['New', 'Confirmed', 'Prep', 'Ready Delivery', 'Completed'];
            
            $currentStatusVal = $booking->status?->value ?? 'pending';

            if ($currentStatusVal === 'cancelled') {
                $flowSteps = ['New', 'Terminated'];
                $currentStep = 2;
            } else {
                $currentStep = match($currentStatusVal) {
                    'pending' => 1,
                    'confirmed' => 2,
                    'in_preparation' => 3,
                    'ready_for_delivery' => 4,
                    'completed' => 5,
                    default => 1,
                };
            }
        @endphp

        <div class="overflow-x-auto -mx-4 px-4 sm:mx-0 sm:px-0">
            <x-ui.status-flow :steps="$flowSteps" :currentStep="$currentStep" />
        </div>
    </div>

    {{-- Page Header --}}
    <div class="flex flex-col gap-4 sm:gap-6">
        <div class="space-y-2">
            <div class="flex flex-wrap items-center gap-3">
                <h1 class="text-[22px] sm:text-[28px] font-semibold text-base-content">
                    {{ $booking->reference }}
                </h1>

                @if($booking->booking_type)
                    <span @class([
                        'inline-flex items-center px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-widest border',
                        'bg-primary/10 text-primary border-primary/20' => $booking->booking_type === \App\Enums\BookingType::Event,
                        'bg-base-content/5 text-base-content/60 border-base-content/10' => $booking->booking_type === \App\Enums\BookingType::Meal,
                    ])>
                        {{ $booking->booking_type->value }}
                    </span>
                @endif

                @php
                    $paymentColor = match($booking->payment_status?->value) {
                        'paid' => 'text-success',
                        'pending' => 'text-warning',
                        'unpaid' => 'text-error',
                        default => 'text-base-content/40'
                    };
                @endphp
                <x-badge :type="$booking->status?->value ?? 'pending'" dot>
                    {{ str($booking->status?->value ?? 'pending')->replace('_', ' ')->title() }}
                </x-badge>
                <div class="inline-flex items-center gap-1.5 {{ $paymentColor }} text-[12px] font-bold uppercase tracking-wide">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 opacity-50" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    {{ str($booking->payment_status?->value ?? 'unpaid')->replace('_', ' ')->title() }}
                </div>
            </div>
            <p class="text-[13px] sm:text-[14px] text-base-content/50 mt-1 flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 opacity-50 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
                {{ __('Booking created on') }} <span class="font-medium text-base-content">{{ $booking->created_at->format('M d, Y') }}</span> {{ __('at') }} <span class="font-medium text-base-content">{{ $booking->created_at->format('h:i A') }}</span>
            </p>
        </div>

        <div class="flex flex-wrap items-center justify-end gap-3">
            @if($booking->payment_status?->value === 'paid')
                <x-ui.button variant="success" class="border" size="sm" href="{!! app(\App\Services\InvoiceService::class)->getDownloadUrl($booking) !!}" target="_blank" title="{{ __('Download Invoice') }}">
                    <x-slot:icon>
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                    </x-slot:icon>
                    {{ __('Invoice') }}
                </x-ui.button>
            @endif

             <x-ui.button variant="black" class="border-0" size="sm" href="{{ $booking->booking_type === \App\Enums\BookingType::Event ? route('admin.events.index') : route('admin.bookings.index') }}" wire:navigate title="{{ __('Back') }}">
                <x-slot:icon>
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                </x-slot:icon>
                {{ $booking->booking_type === \App\Enums\BookingType::Event ? __('Back to Events') : __('Back to Bookings') }}
             </x-ui.button>
        </div>
    </div>

    {{-- Main Content Grid --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 lg:gap-8">
        {{-- Left: Details & Packages (2/3) --}}
        <div class="lg:col-span-2 space-y-6 sm:space-y-8">

            {{-- Customer & Event Cards --}}
            <div class="grid grid-cols-1 {{ $booking->booking_type === \App\Enums\BookingType::Event ? 'sm:grid-cols-2' : '' }} gap-6">
                {{-- Customer Details Card --}}
                <x-ui.card>
                    <div class="flex items-center gap-2.5 mb-6">
                        <div class="w-8 h-8 rounded-full bg-[#F96015]/10 text-[#F96015] flex items-center justify-center flex-shrink-0">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                        </div>
                        <h2 class="text-[11px] font-bold uppercase tracking-[0.2em] text-base-content">{{ __('Customer Details') }}</h2>
                    </div>

                    <div class="space-y-5">
                        <div>
                            <p class="text-[10px] font-bold text-base-content/60 uppercase tracking-widest mb-1 opacity-50">{{ __('Client Name') }}</p>
                            <p class="text-[18px] sm:text-[20px] font-semibold text-base-content">{{ $booking->customer->name }}</p>
                        </div>
                        <div>
                            <p class="text-[10px] font-bold text-base-content/60 uppercase tracking-widest mb-2 opacity-50">{{ __('Contact Info') }}</p>
                            <div class="flex flex-col gap-2.5">
                                @if($booking->customer->email)
                                    <a href="mailto:{{ $booking->customer->email }}" class="flex items-center gap-3 text-[13px] text-base-content border border-base-content/10 p-3 rounded-xl hover:bg-[#F96015]/5 hover:border-[#F96015]/20 transition-colors min-w-0">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-[#F96015] flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                        </svg>
                                        <span class="truncate">{{ $booking->customer->email }}</span>
                                    </a>
                                @endif
                                <a href="tel:{{ $booking->customer->phone }}" class="flex items-center gap-3 text-[13px] text-base-content border border-base-content/10 p-3 rounded-xl hover:bg-[#F96015]/5 hover:border-[#F96015]/20 transition-colors">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-[#F96015] flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h2.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                    </svg>
                                    {{ $booking->customer->phone }}
                                </a>
                            </div>
                        </div>
                    </div>
                </x-ui.card>

                {{-- Event Details Card (only for event bookings) --}}
                @if($booking->booking_type === \App\Enums\BookingType::Event)
                <x-ui.card>
                    <div class="flex items-center gap-2.5 mb-6">
                        <div class="w-8 h-8 rounded-full bg-[#FFC926]/15 text-[#FFC926] flex items-center justify-center flex-shrink-0">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                        </div>
                        <h2 class="text-[11px] font-bold uppercase tracking-[0.2em] text-base-content">{{ __('Event Details') }}</h2>
                    </div>

                    <div class="space-y-6">
                        <div class="flex items-center gap-5">
                            <div class="flex flex-col">
                                <p class="text-[10px] font-bold text-base-content/60 uppercase tracking-widest mb-1 opacity-50">{{ __('Date') }}</p>
                                <p class="text-[14px] sm:text-[15px] font-bold text-base-content">
                                    {{ $booking->event_date ? $booking->event_date->format('M d, Y') : 'No Date' }}
                                </p>
                            </div>
                            <div class="h-8 w-px border-l border-base-content/5"></div>
                            <div class="flex flex-col">
                                <p class="text-[10px] font-bold text-base-content/60 uppercase tracking-widest mb-1 opacity-50">{{ __('Guests') }}</p>
                                <p class="text-[14px] sm:text-[15px] font-bold text-base-content">
                                    {{ $booking->pax ?? '--' }} {{ $booking->is_buffet ? __('Guests (Buffet)') : __('Plates') }}
                                </p>
                            </div>
                        </div>

                        <div>
                            <p class="text-[10px] font-bold text-base-content/60 uppercase tracking-widest mb-1 opacity-50">{{ __('Timeline') }}</p>
                            <p class="text-[14px] sm:text-[15px] font-bold text-base-content flex items-center gap-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 opacity-30 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                {{ $booking->event_start_time ? \Carbon\Carbon::parse($booking->event_start_time)->format('h:i A') : '--' }}
                                <span class="text-base-content/60 opacity-40 mx-1">&rarr;</span>
                                {{ $booking->event_end_time ? \Carbon\Carbon::parse($booking->event_end_time)->format('h:i A') : '--' }}
                            </p>
                        </div>

                        <div>
                            <p class="text-[10px] font-bold text-base-content/60 uppercase tracking-widest mb-1 opacity-50">{{ __('Event Type') }}</p>
                            <x-badge type="ghost" class="mt-1">{{ $booking->event_type?->value ?? 'No Event Type' }}</x-badge>
                        </div>

                        @if($booking->customer_notes)
                            <div>
                                <p class="text-[10px] font-bold text-base-content/60 uppercase tracking-widest mb-1 opacity-50">{{ __('Customer Notes') }}</p>
                                <p class="text-[13px] text-base-content/70 font-medium leading-relaxed">{{ $booking->customer_notes }}</p>
                            </div>
                        @endif
                    </div>
                </x-ui.card>
                @endif

                {{-- Payment Details Card --}}
                @if($booking->payment_channel || $booking->payment_reference)
                <x-ui.card>
                    <div class="flex items-center gap-2.5 mb-6">
                        <div class="w-8 h-8 rounded-full bg-primary/10 text-primary flex items-center justify-center flex-shrink-0">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                            </svg>
                        </div>
                        <h2 class="text-[11px] font-bold uppercase tracking-[0.2em] text-base-content">{{ __('Payment Details') }}</h2>
                    </div>

                    <div class="grid grid-cols-2 gap-6">
                        @if($booking->payment_channel)
                        <div class="flex flex-col">
                            <p class="text-[10px] font-bold text-base-content/60 uppercase tracking-widest mb-1 opacity-50">{{ __('Payment Channel') }}</p>
                            <p class="text-[14px] sm:text-[15px] font-bold text-base-content">
                                @if($booking->payment_channel === '13')
                                    MTN MoMo
                                @elseif($booking->payment_channel === '6')
                                    Telecel
                                @elseif($booking->payment_channel === '7')
                                    AT Money
                                @else
                                    {{ $booking->payment_channel }}
                                @endif
                            </p>
                        </div>
                        @endif

                        @if($booking->payer_number)
                        <div class="flex flex-col">
                            <p class="text-[10px] font-bold text-base-content/60 uppercase tracking-widest mb-1 opacity-50">{{ __('Payer Number') }}</p>
                            <p class="text-[14px] sm:text-[15px] font-bold text-base-content">{{ $booking->payer_number }}</p>
                        </div>
                        @endif

                        @if($booking->payment_reference)
                        <div class="flex flex-col col-span-2">
                            <p class="text-[10px] font-bold text-base-content/60 uppercase tracking-widest mb-1 opacity-50">{{ __('Transaction Reference') }}</p>
                            <p class="text-[13px] font-mono font-medium text-base-content/80 break-all">{{ $booking->payment_reference }}</p>
                        </div>
                        @endif
                    </div>
                </x-ui.card>
                @endif

            </div>

            {{-- Packages / Invoice Table --}}
            <x-ui.card padding="none">
                <div class="p-5 sm:p-8 pb-4">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                        <div class="flex items-center gap-2.5">
                            <div class="w-8 h-8 rounded-full bg-[#9ABC05]/10 text-[#9ABC05] flex items-center justify-center flex-shrink-0">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                            </div>
                            <h2 class="text-[11px] font-bold uppercase tracking-[0.2em] text-base-content">{{ __('Packages & Invoice') }}</h2>
                        </div>
                        @if($booking->payment_status?->value === 'paid')
                            <a href="{!! app(\App\Services\InvoiceService::class)->getDownloadUrl($booking) !!}" target="_blank" class="flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-[#F96015]/10 text-[#F96015] text-[12px] font-bold hover:bg-[#F96015]/20 transition-colors">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                                {{ __('Download Invoice') }}
                            </a>
                        @endif
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <x-ui.table>
                        <x-slot:header>
                            <x-ui.table.header>{{ __('Package description') }}</x-ui.table.header>
                            @if($booking->booking_type !== \App\Enums\BookingType::Event)
                                <x-ui.table.header class="text-right">{{ __('Cost') }}</x-ui.table.header>
                            @endif
                        </x-slot:header>

                        @forelse ($booking->items as $item)
                            <x-ui.table.row>
                                <x-ui.table.cell>
                                    <div class="text-[13px] sm:text-[14px] font-bold text-base-content">{{ $item->package_name ?? $item->package?->name ?? 'Custom Package' }}</div>
                                </x-ui.table.cell>
                                @if($booking->booking_type !== \App\Enums\BookingType::Event)
                                    <x-ui.table.cell class="text-right">
                                        <span class="text-[13px] sm:text-[14px] font-bold text-base-content whitespace-nowrap">GHS {{ number_format($item->price, 2) }}</span>
                                    </x-ui.table.cell>
                                @endif
                            </x-ui.table.row>
                        @empty
                            <x-ui.table.row>
                                <x-ui.table.cell colspan="{{ $booking->booking_type === \App\Enums\BookingType::Event ? 1 : 2 }}" class="text-center py-10 opacity-40 italic">{{ __('No packages assigned to this booking.') }}</x-ui.table.cell>
                            </x-ui.table.row>
                        @endforelse

                        <x-slot:footer>
                            <tr class="bg-base-200 border-t border-base-content/5">
                                <td class="px-5 sm:px-8 py-4 sm:py-5 text-right text-[12px] sm:text-[13px] font-bold text-base-content/60 uppercase tracking-widest">{{ __('Total Amount') }}</td>
                                <td class="px-5 sm:px-8 py-4 sm:py-5 text-right text-[18px] sm:text-[22px] font-bold whitespace-nowrap {{ $booking->booking_type === \App\Enums\BookingType::Event && $booking->total_amount == 0 ? 'text-warning' : 'text-[#F96015]' }}">
                                    @if($booking->booking_type === \App\Enums\BookingType::Event && $booking->total_amount == 0)
                                        {{ __('Quote Pending') }}
                                    @else
                                        GHS {{ number_format($booking->total_amount, 2) }}
                                    @endif
                                </td>
                            </tr>
                        </x-slot:footer>
                    </x-ui.table>
                </div>
            </x-ui.card>

        </div>

        {{-- Right: Sidebar Action Panel (1/3) --}}
        <div class="space-y-6 sm:space-y-8">

            {{-- Manage Booking Card --}}
            <x-ui.card class="relative overflow-hidden border-0 shadow-xl shadow-base-content/5 ring-1 ring-base-content/10">
                <div class="absolute -top-24 -right-24 w-48 h-48 bg-primary/5 rounded-full blur-3xl pointer-events-none"></div>
                <div class="absolute -bottom-24 -left-24 w-48 h-48 bg-secondary/5 rounded-full blur-3xl pointer-events-none"></div>

                <div class="relative flex items-center gap-3 mb-8">
                    <div class="w-10 h-10 rounded-2xl bg-gradient-to-br from-[#1c1c1c] to-[#0a0a0a] flex items-center justify-center shadow-lg shadow-black/20 ring-1 ring-white/10 shrink-0">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-[12px] font-black uppercase tracking-[0.25em] text-base-content">{{ __('Manage Booking') }}</h2>
                        <p class="text-[10px] text-base-content/50 uppercase tracking-widest mt-0.5">{{ __('Available actions') }}</p>
                    </div>
                </div>

                <div class="relative space-y-4">
                    @if($this->canEditEvent)
                        <button class="group w-full flex items-center justify-center gap-2 py-4 px-5 rounded-lg bg-gradient-to-r from-[#F96015] to-[#e5520d] text-white text-[14px] font-bold shadow-[0_8px_16px_-4px_rgba(249,96,21,0.4)] hover:shadow-[0_12px_20px_-4px_rgba(249,96,21,0.5)] hover:-translate-y-1 transition-all duration-300 border border-white/10 overflow-hidden relative" wire:click="openEventEditModal">
                            <div class="absolute inset-0 bg-white/20 translate-y-full group-hover:translate-y-0 transition-transform duration-300 ease-out z-0"></div>
                            <svg xmlns="http://www.w3.org/2000/svg" class="relative z-10 w-5 h-5 drop-shadow-md" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                            </svg>
                            <span class="relative z-10 tracking-wide">{{ __('Update Event Details') }}</span>
                        </button>
                    @endif

                    @if($this->canBeConfirmed)
                        <button class="group w-full flex items-center justify-center gap-2 py-4 px-5 rounded-lg bg-gradient-to-r from-[#18542A] to-[#206f38] text-white text-[14px] font-bold shadow-[0_8px_16px_-4px_rgba(24,84,42,0.4)] hover:shadow-[0_12px_20px_-4px_rgba(24,84,42,0.5)] hover:-translate-y-1 transition-all duration-300 border border-white/10 overflow-hidden relative" wire:click="promptAction('confirmBooking')">
                                <div class="absolute inset-0 bg-white/20 translate-y-full group-hover:translate-y-0 transition-transform duration-300 ease-out z-0"></div>
                                <svg xmlns="http://www.w3.org/2000/svg" class="relative z-10 w-5 h-5 drop-shadow-md" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                                <span class="relative z-10 tracking-wide">{{ __('Verify & Confirm') }}</span>
                        </button>
                    @endif

                    @if(($booking->status?->value ?? 'pending') === 'confirmed' && !$this->canBePrepared)
                        <div class="p-4 sm:p-5 bg-gradient-to-br from-error/10 to-error/5 border border-error/20 rounded-lg space-y-4 shadow-inner ring-1 ring-error/5">
                            <div class="flex items-start gap-3 sm:gap-4">
                                <div class="mt-0.5 w-8 h-8 rounded-full bg-error/20 flex items-center justify-center shrink-0">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-error" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m0 0v2m0-2h2m-2 0H8m13 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-[12px] sm:text-[13px] font-black text-error uppercase tracking-widest">{{ __('Awaiting Payment') }}</p>
                                    <p class="text-[11px] sm:text-[12px] text-error/80 mt-1.5 leading-relaxed font-medium">
                                        {{ __('Preparation cannot start until payment is confirmed.') }}
                                    </p>
                                </div>
                            </div>

                            @if($this->canBeVerified)
                                <button class="w-full flex items-center justify-center gap-2 py-3 px-4 rounded-lg bg-error/90 hover:bg-error text-white text-[13px] font-bold shadow-md hover:shadow-lg transition-all duration-300 backdrop-blur-sm" wire:click="openVerifyModal">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    {{ __('Verify Payment Manually') }}
                                </button>
                            @endif
                        </div>
                    @endif

                    @if($this->canBePrepared)
                        <button class="group w-full flex items-center justify-center gap-2 py-4 px-5 rounded-lg bg-gradient-to-r from-[#FFC926] to-[#ffb114] text-black text-[14px] font-bold shadow-[0_8px_16px_-4px_rgba(255,201,38,0.4)] hover:shadow-[0_12px_20px_-4px_rgba(255,201,38,0.5)] hover:-translate-y-1 transition-all duration-300 border border-black/5 overflow-hidden relative" wire:click="promptAction('startPreparation')">
                                <div class="absolute inset-0 bg-white/30 translate-y-full group-hover:translate-y-0 transition-transform duration-300 ease-out z-0"></div>
                                <svg xmlns="http://www.w3.org/2000/svg" class="relative z-10 w-5 h-5 opacity-80" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4" />
                                </svg>
                                <span class="relative z-10 tracking-wide">{{ __('Start Preparation') }}</span>
                        </button>
                    @endif

                    @if($this->canBeDispatched)
                        <button class="group w-full flex items-center justify-center gap-2 py-4 px-5 rounded-lg bg-gradient-to-r from-[#8B5CF6] to-[#A855F7] text-white text-[14px] font-bold shadow-[0_8px_16px_-4px_rgba(139,92,246,0.4)] hover:shadow-[0_12px_20px_-4px_rgba(139,92,246,0.5)] hover:-translate-y-1 transition-all duration-300 border border-white/10 overflow-hidden relative" wire:click="promptAction('readyForDelivery')">
                            <div class="absolute inset-0 bg-white/20 translate-y-full group-hover:translate-y-0 transition-transform duration-300 ease-out z-0"></div>
                            <svg xmlns="http://www.w3.org/2000/svg" class="relative z-10 w-5 h-5 drop-shadow-md" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4" />
                            </svg>
                            <span class="relative z-10 tracking-wide">{{ __('Ready For Delivery') }}</span>
                        </button>
                    @endif

                    @if($this->canBeCompleted)
                        <button class="group w-full flex items-center justify-center gap-2 py-4 px-5 rounded-lg bg-gradient-to-r from-[#18542A] to-[#206f38] text-white text-[14px] font-bold shadow-[0_8px_16px_-4px_rgba(24,84,42,0.4)] hover:shadow-[0_12px_20px_-4px_rgba(24,84,42,0.5)] hover:-translate-y-1 transition-all duration-300 border border-white/10 overflow-hidden relative" wire:click="promptAction('completeBooking')">
                                <div class="absolute inset-0 bg-white/20 translate-y-full group-hover:translate-y-0 transition-transform duration-300 ease-out z-0"></div>
                                <svg xmlns="http://www.w3.org/2000/svg" class="relative z-10 w-5 h-5 drop-shadow-md" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <span class="relative z-10 tracking-wide">{{ __('Complete Booking') }}</span>
                        </button>
                    @endif

                    @if(($booking->status?->value ?? 'pending') === 'completed')
                        <div class="px-5 py-4 bg-success/10 border border-success/20 rounded-lg flex items-center gap-4">
                            <div class="w-8 h-8 rounded-full bg-success/20 flex items-center justify-center shrink-0">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-success" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                            </div>
                            <p class="text-[13px] font-bold text-success">{{ __('This booking has been successfully completed.') }}</p>
                        </div>
                    @endif

                    @if(($booking->status?->value ?? 'pending') === 'cancelled')
                        <div class="p-4 sm:p-5 bg-error/10 border border-error/20 rounded-lg shadow-inner">
                            <div class="flex items-center gap-2 mb-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-error" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <p class="font-bold text-[14px] text-error">{{ __('Cancellation Confirmed') }}</p>
                            </div>
                            <p class="text-[12px] text-error/80 mt-1 font-medium">{{ $booking->cancelled_reason ?: 'No justification provided.' }}</p>
                        </div>
                    @endif

                    @if($this->canBeCancelled)
                        <div class="relative py-4">
                            <div class="absolute inset-0 flex items-center" aria-hidden="true">
                                <div class="w-full border-t border-base-content/10"></div>
                            </div>
                            <div class="relative flex justify-center">
                                <span class="bg-base-100 px-3 text-[10px] font-black uppercase tracking-widest text-base-content/40">{{ __('Danger Zone') }}</span>
                            </div>
                        </div>

                        <button class="w-full flex items-center justify-center gap-2 py-3 px-5 rounded-lg border border-[#D52518]/30 text-[#D52518] text-[13px] font-bold bg-[#D52518]/5 hover:bg-[#D52518]/10 hover:border-[#D52518]/50 transition-all duration-300 group" wire:click="openCancelModal">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 group-hover:scale-110 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                            {{ __('Cancel Booking') }}
                        </button>
                    @endif

                    <div class="relative py-2">
                        <div class="absolute inset-0 flex items-center" aria-hidden="true">
                            <div class="w-full border-t border-base-content/10"></div>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <a href="tel:{{ $booking->customer->phone }}" class="flex items-center justify-center gap-2 py-3 px-4 rounded-xl bg-base-content/5 hover:bg-base-content/10 text-base-content text-[13px] font-bold transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-[#F96015]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h2.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                            </svg>
                            {{ __('Call') }}
                        </a>
                        @if($booking->customer->email)
                            <a href="mailto:{{ $booking->customer->email }}" class="flex items-center justify-center gap-2 py-3 px-4 rounded-xl bg-base-content/5 hover:bg-base-content/10 text-base-content text-[13px] font-bold transition-colors">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-[#F96015]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                </svg>
                                {{ __('Email') }}
                            </a>
                        @endif
                    </div>
                </div>
            </x-ui.card>

            {{-- Booking Timeline --}}
            <div class="relative rounded-xl p-[1px] shadow-sm">
                <div class="p-5 sm:p-6 rounded-[calc(1.5rem-1px)] bg-base-100 h-full w-full relative overflow-hidden backdrop-blur-xl">
                    <div class="absolute -right-6 -bottom-6 opacity-[0.02] text-base-content pointer-events-none">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-32 h-32" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>

                    <h3 class="text-[10px] font-black uppercase tracking-[0.2em] text-base-content/40 mb-5">{{ __('Booking Timeline') }}</h3>

                    <div class="space-y-5 relative z-10">
                        <div class="flex flex-col gap-1.5 border-l-2 border-[#F96015]/30 pl-3">
                            <span class="text-[10px] font-bold text-base-content/60 uppercase tracking-widest">{{ __('Created') }}</span>
                            <span class="text-[13px] font-bold text-base-content">{{ $booking->created_at->format('M d, Y') }} <span class="text-[11px] text-base-content/40 font-normal ml-1">({{ $booking->created_at->diffForHumans() }})</span></span>
                        </div>

                        @if($booking->confirmed_at)
                        <div class="flex flex-col gap-1.5 border-l-2 border-[#FFC926]/30 pl-3">
                            <span class="text-[10px] font-bold text-base-content/60 uppercase tracking-widest">{{ __('Confirmed By') }}</span>
                            <span class="text-[13px] font-bold text-base-content">{{ $booking->confirmedBy->name ?? 'System' }}</span>
                        </div>
                        @endif

                        <div class="flex flex-col gap-1.5 border-l-2 border-[#9ABC05]/30 pl-3">
                            <span class="text-[10px] font-bold text-base-content/60 uppercase tracking-widest">{{ __('Last Updated') }}</span>
                            <span class="text-[13px] font-bold text-base-content">{{ $booking->updated_at->format('M d, H:i') }}</span>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

    {{-- Cancellation Modal --}}
    <x-ui.modal wire:model="showCancelModal" title="Confirm Termination" icon="heroicon-o-exclamation-triangle" persistent>
        <div class="space-y-6">
            <p class="text-[14px] text-base-content leading-relaxed">
                {{ __('Are you sure you want to cancel booking') }} <strong class="text-primary">{{ $booking->reference }}</strong>? {{ __('This action cannot be undone.') }}
            </p>

            <div class="space-y-2">
                <label class="text-[11px] font-bold uppercase tracking-widest text-base-content/60">{{ __('Cancellation Reason (Optional)') }}</label>
                <x-ui.textarea wire:model="cancelReason" placeholder="Enter reason for cancellation..." />
            </div>
        </div>

        <x-slot:footer>
            <x-ui.button variant="ghost" wire:click="closeCancelModal">{{ __('Cancel') }}</x-ui.button>
            <x-ui.button type="danger" variant="primary" wire:click="cancelBooking" class="bg-dp-danger border-dp-danger hover:bg-dp-danger-hover">
                {{ __('Confirm Cancellation') }}
            </x-ui.button>
        </x-slot:footer>
    </x-ui.modal>

    {{-- Action Confirmation Modal --}}
    <x-ui.modal wire:model="showActionModal" title="Confirm Action" icon="heroicon-o-information-circle">
        <div class="py-2">
            <p class="text-[15px] text-base-content leading-relaxed">
                {{ __('Are you sure you want to proceed with') }}
                @if($actionToConfirm === 'confirmBooking')
                    <strong class="text-primary uppercase tracking-widest">{{ __('Verification & Confirmation') }}</strong>
                @elseif($actionToConfirm === 'startPreparation')
                    <strong class="text-secondary uppercase tracking-widest">{{ __('Start Preparation') }}</strong>
                @elseif($actionToConfirm === 'readyForDelivery')
                    <strong class="text-[#A855F7] uppercase tracking-widest">{{ __('Ready For Delivery') }}</strong>
                @elseif($actionToConfirm === 'completeBooking')
                    <strong class="text-dp-success uppercase tracking-widest">{{ __('Complete Booking') }}</strong>
                @else
                    {{ __('this update') }}
                @endif
                {{ __('for booking') }} <strong class="text-base-content">{{ $booking->reference }}</strong>?
            </p>
        </div>

        <x-slot:footer>
            <x-ui.button variant="ghost" wire:click="closeActionModal">{{ __('Cancel') }}</x-ui.button>
            <x-ui.button variant="primary" wire:click="executeAction">{{ __('Confirm') }}</x-ui.button>
        </x-slot:footer>
    </x-ui.modal>

    {{-- Payment Verification Modal --}}
    <x-ui.modal wire:model="showVerifyModal" title="Manual Payment Verification" icon="heroicon-o-banknotes">
        <div class="space-y-6">
            <p class="text-[14px] text-base-content leading-relaxed">
                {{ __('You are manually verifying payment for booking') }} <strong class="text-primary">{{ $booking->reference }}</strong>. {{ __('This will unlock the preparation phase immediately.') }}
            </p>

            <div class="space-y-2">
                <label class="text-[11px] font-bold uppercase tracking-widest text-base-content/60">{{ __('Verification Notes (Optional)') }}</label>
                <x-ui.textarea wire:model="verificationNotes" placeholder="Describe the payment source (e.g., GH₵ 500 cash received, bank transfer confirmed...)" />
            </div>
        </div>

        <x-slot:footer>
            <x-ui.button variant="ghost" wire:click="closeVerifyModal">{{ __('Cancel') }}</x-ui.button>
            <x-ui.button type="success" variant="primary" wire:click="verifyPayment" class="bg-secondary border-dp-green hover:bg-secondary-hover">
                {{ __('Confirm & Unlock Preparation') }}
            </x-ui.button>
        </x-slot:footer>
    </x-ui.modal>

    {{-- Quote Modal (kept for backward compatibility / non-event use) --}}
    <x-ui.modal wire:model="showQuoteModal" title="Set Event Quote" icon="heroicon-o-currency-dollar" persistent>
        <div class="space-y-6">
            <p class="text-[14px] text-base-content leading-relaxed">
                {{ __('Set the total quote for event booking') }} <strong class="text-primary">{{ $booking->reference }}</strong>. {{ __('The customer will be notified via email and SMS.') }}
            </p>

            @if($booking->total_amount > 0)
                <div class="px-4 py-3 bg-base-200 rounded-xl border border-base-content/10">
                    <p class="text-[11px] font-bold text-base-content/60 uppercase tracking-widest mb-1">{{ __('Current Amount') }}</p>
                    <p class="text-[18px] font-bold text-base-content">GH₵ {{ number_format($booking->total_amount, 2) }}</p>
                </div>
            @endif

            <div class="space-y-2">
                <label class="text-[11px] font-bold uppercase tracking-widest text-base-content/60">{{ __('Quote Amount (GH₵)') }}</label>
                <input type="number" wire:model="quoteAmount" step="0.01" min="0" placeholder="e.g. 500.00"
                    class="w-full px-5 py-4 bg-base-200 border border-base-content/10 focus:border-primary focus:ring-4 focus:ring-primary/20 rounded-xl transition-all text-[15px] font-medium placeholder:text-base-content/30">
                @error('quoteAmount') <span class="text-xs font-bold text-error mt-1 block">{{ $message }}</span> @enderror
            </div>
        </div>

        <x-slot:footer>
            <x-ui.button variant="ghost" wire:click="closeQuoteModal">{{ __('Cancel') }}</x-ui.button>
            <x-ui.button variant="primary" wire:click="updateQuote" wire:loading.attr="disabled">
                <span wire:loading.remove wire:target="updateQuote">{{ __('Send Quote') }}</span>
                <span wire:loading wire:target="updateQuote">{{ __('Sending...') }}</span>
            </x-ui.button>
        </x-slot:footer>
    </x-ui.modal>

    {{-- Update Event Details Modal --}}
    <x-ui.modal wire:model="showEventEditModal" title="Update Event Details" icon="heroicon-o-pencil-square" persistent>
        <div class="space-y-5">
            <p class="text-[14px] text-base-content leading-relaxed">
                {{ __('Update the event details and quote for') }} <strong class="text-primary">{{ $booking->reference }}</strong>. {{ __('Enter your password to approve and notify the customer.') }}
            </p>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                {{-- Event Date --}}
                <div class="space-y-2">
                    <label class="text-[11px] font-bold uppercase tracking-widest text-base-content/60">{{ __('Event Date') }}</label>
                    <input type="date" wire:model="editEventDate"
                        class="w-full px-4 py-3 bg-base-200 border border-base-content/10 focus:border-primary focus:ring-4 focus:ring-primary/20 rounded-xl transition-all text-[14px] font-medium">
                    @error('editEventDate') <span class="text-xs font-bold text-error mt-1 block">{{ $message }}</span> @enderror
                </div>

                {{-- Event Type --}}
                <div class="space-y-2">
                    <label class="text-[11px] font-bold uppercase tracking-widest text-base-content/60">{{ __('Event Type') }}</label>
                    <select wire:model="editEventType"
                        class="w-full px-4 py-3 bg-base-200 border border-base-content/10 focus:border-primary focus:ring-4 focus:ring-primary/20 rounded-xl transition-all text-[14px] font-medium">
                        <option value="">{{ __('Select Type') }}</option>
                        @foreach(\App\Enums\EventType::cases() as $type)
                            <option value="{{ $type->value }}">{{ str($type->value)->title()->replace('_', ' ') }}</option>
                        @endforeach
                    </select>
                    @error('editEventType') <span class="text-xs font-bold text-error mt-1 block">{{ $message }}</span> @enderror
                </div>

                {{-- Start Time --}}
                <div class="space-y-2">
                    <label class="text-[11px] font-bold uppercase tracking-widest text-base-content/60">{{ __('Start Time') }}</label>
                    <input type="time" wire:model="editEventStartTime"
                        class="w-full px-4 py-3 bg-base-200 border border-base-content/10 focus:border-primary focus:ring-4 focus:ring-primary/20 rounded-xl transition-all text-[14px] font-medium">
                    @error('editEventStartTime') <span class="text-xs font-bold text-error mt-1 block">{{ $message }}</span> @enderror
                </div>

                {{-- End Time --}}
                <div class="space-y-2">
                    <label class="text-[11px] font-bold uppercase tracking-widest text-base-content/60">{{ __('End Time') }}</label>
                    <input type="time" wire:model="editEventEndTime"
                        class="w-full px-4 py-3 bg-base-200 border border-base-content/10 focus:border-primary focus:ring-4 focus:ring-primary/20 rounded-xl transition-all text-[14px] font-medium">
                    @error('editEventEndTime') <span class="text-xs font-bold text-error mt-1 block">{{ $message }}</span> @enderror
                </div>

                {{-- Pax --}}
                <div class="space-y-2">
                    <label class="text-[11px] font-bold uppercase tracking-widest text-base-content/60">{{ __('Number of Guests') }}</label>
                    <input type="number" wire:model="editPax" min="1" placeholder="e.g. 100"
                        class="w-full px-4 py-3 bg-base-200 border border-base-content/10 focus:border-primary focus:ring-4 focus:ring-primary/20 rounded-xl transition-all text-[14px] font-medium placeholder:text-base-content/30">
                    @error('editPax') <span class="text-xs font-bold text-error mt-1 block">{{ $message }}</span> @enderror
                </div>

                {{-- Buffet Toggle --}}
                <div class="space-y-2">
                    <label class="text-[11px] font-bold uppercase tracking-widest text-base-content/60">{{ __('Service Style') }}</label>
                    <div class="flex items-center gap-4 py-3">
                        <label class="inline-flex items-center gap-2 cursor-pointer">
                            <input type="radio" wire:model="editIsBuffet" value="0" class="radio radio-sm radio-primary">
                            <span class="text-[14px] font-medium text-base-content">{{ __('Plates') }}</span>
                        </label>
                        <label class="inline-flex items-center gap-2 cursor-pointer">
                            <input type="radio" wire:model="editIsBuffet" value="1" class="radio radio-sm radio-primary">
                            <span class="text-[14px] font-medium text-base-content">{{ __('Buffet') }}</span>
                        </label>
                    </div>
                </div>
            </div>

            {{-- Quote Amount --}}
            <div class="space-y-2 pt-2 border-t border-base-content/10">
                <label class="text-[11px] font-bold uppercase tracking-widest text-base-content/60">{{ __('Quote Amount (GH₵)') }}</label>
                <input type="number" wire:model="editQuoteAmount" step="0.01" min="0" placeholder="e.g. 500.00"
                    class="w-full px-5 py-4 bg-base-200 border border-base-content/10 focus:border-primary focus:ring-4 focus:ring-primary/20 rounded-xl transition-all text-[15px] font-bold placeholder:text-base-content/30">
                @error('editQuoteAmount') <span class="text-xs font-bold text-error mt-1 block">{{ $message }}</span> @enderror
            </div>

            {{-- Password Confirmation --}}
            <div class="space-y-2 pt-2 border-t border-base-content/10">
                <label class="text-[11px] font-bold uppercase tracking-widest text-error/80">{{ __('Enter Password to Approve') }}</label>
                <input type="password" wire:model="confirmPassword" placeholder="Enter your password..."
                    class="w-full px-5 py-4 bg-base-200 border border-base-content/10 focus:border-error focus:ring-4 focus:ring-error/20 rounded-xl transition-all text-[15px] font-medium placeholder:text-base-content/30">
                @error('confirmPassword') <span class="text-xs font-bold text-error mt-1 block">{{ $message }}</span> @enderror
            </div>
        </div>

        <x-slot:footer>
            <x-ui.button variant="ghost" wire:click="closeEventEditModal">{{ __('Cancel') }}</x-ui.button>
            <x-ui.button variant="primary" wire:click="updateEventDetails" wire:loading.attr="disabled">
                <span wire:loading.remove wire:target="updateEventDetails">{{ __('Approve & Notify Customer') }}</span>
                <span wire:loading wire:target="updateEventDetails">{{ __('Processing...') }}</span>
            </x-ui.button>
        </x-slot:footer>
    </x-ui.modal>
</div>
