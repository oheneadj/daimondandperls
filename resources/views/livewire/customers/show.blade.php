<div class="space-y-10 pb-16">
    <!-- Breadcrumbs & Header -->
    <div class="flex items-center justify-between mb-8">
        <div class="flex items-center gap-2 text-[11px] font-bold uppercase tracking-widest text-base-content/60">
            <a href="{{ route('admin.customers.index') }}" wire:navigate class="hover:text-[#F96015] transition-colors">{{ __('Customers') }}</a>
            <svg xmlns="http://www.w3.org/2000/svg" class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
            </svg>
            <span class="text-base-content">{{ $customer->name }}</span>
        </div>

        <div class="flex items-center gap-3">
             <x-ui.button variant="black" size="sm" href="{{ route('admin.customers.index') }}" wire:navigate class="border-0">
                <x-slot:icon>
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                </x-slot:icon>
                {{ __('Back to List') }}
             </x-ui.button>
        </div>
    </div>
    
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-6 mb-10">
        <div class="flex items-center gap-6">
            <div class="w-16 h-16 rounded-xl bg-[#F96015] text-white flex items-center justify-center text-[24px] font-bold shadow-lg shadow-[#F96015]/20 shrink-0">
                {{ substr($customer->name, 0, 2) }}
            </div>
            <div class="space-y-1">
                <h1 class="text-[32px] font-semibold text-base-content leading-tight">{{ $customer->name }}</h1>
                <div class="flex items-center gap-2">
                    <x-ui.badge type="success" dot class="font-bold text-[10px] uppercase tracking-widest">
                        {{ __('Active Customer') }}
                    </x-ui.badge>
                    @if($customer->user_id)
                        <x-ui.badge type="info" dot class="font-bold text-[10px] uppercase tracking-widest">
                            {{ __('Linked Account') }}
                        </x-ui.badge>
                    @else
                        <x-ui.badge type="neutral" dot class="font-bold text-[10px] uppercase tracking-widest">
                            {{ __('Guest') }}
                        </x-ui.badge>
                    @endif
                </div>
            </div>
        </div>
        
        <div class="flex items-center gap-3">
            @if($customer->user_id)
                <x-ui.button 
                    wire:click="impersonate" 
                    variant="neutral"
                    size="md"
                    class="font-bold"
                >
                    <x-slot:icon>
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 opacity-70" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                    </x-slot:icon>
                    {{ __('Impersonate') }}
                </x-ui.button>
            @endif
            
            <x-ui.button 
                href="{{ route('admin.customers.edit', $customer) }}" 
                wire:navigate
                variant="primary"
                size="md"
                class="font-bold"
            >
                <x-slot:icon>
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 opacity-50" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                </x-slot:icon>
                {{ __('Edit Profile') }}
            </x-ui.button>
        </div>
    </div>

    <div class="grid grid-cols-1 xl:grid-cols-3 gap-8">
        <!-- Main Content Area (Left 2/3) -->
        <div class="xl:col-span-2 space-y-8">
            
            <!-- Stats Matrix -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <x-ui.card class="relative overflow-hidden group">
                    <div class="flex items-center gap-4">
                        <div class="w-10 h-10 rounded-xl bg-[#F96015]/10 flex items-center justify-center text-[#F96015] shrink-0 transition-transform group-hover:scale-110">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-[20px] font-bold text-base-content leading-tight">{{ $customer->bookings->count() }}</p>
                            <p class="text-[10px] font-bold text-base-content/40 uppercase tracking-widest">{{ __('Total Bookings') }}</p>
                        </div>
                    </div>
                </x-ui.card>

                <x-ui.card class="relative overflow-hidden group">
                    <div class="flex items-center gap-4">
                        <div class="w-10 h-10 rounded-xl bg-[#9ABC05]/10 flex items-center justify-center text-[#9ABC05] shrink-0 transition-transform group-hover:scale-110">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-[20px] font-bold text-base-content leading-tight">{{ $customer->bookings->where('status', \App\Enums\BookingStatus::Confirmed)->count() }}</p>
                            <p class="text-[10px] font-bold text-base-content/40 uppercase tracking-widest">{{ __('Confirmed') }}</p>
                        </div>
                    </div>
                </x-ui.card>

                <x-ui.card class="relative overflow-hidden group border-l-4 border-l-[#F96015] shadow-xl shadow-[#F96015]/5">
                    <div class="flex items-center gap-4">
                        <div class="w-10 h-10 rounded-xl bg-[#F96015] flex items-center justify-center text-white shrink-0 shadow-lg shadow-[#F96015]/20">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 1.343-3 3s1.343 3 3 3 3-1.343 3-3-1.343-3-3-3z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-[20px] font-bold text-base-content leading-tight">GH₵{{ number_format($ltv, 0) }}</p>
                            <p class="text-[10px] font-bold text-[#F96015]/60 uppercase tracking-widest">{{ __('Life Spend') }}</p>
                        </div>
                    </div>
                </x-ui.card>
            </div>

            <!-- Interactive Workspace -->
            <div class="space-y-6">
                <!-- Workspace Tabs -->
                <nav class="flex space-x-2 bg-base-300/30 p-1.5 rounded-lg w-fit" aria-label="Customer Tabs">
                    <button 
                        wire:click="setTab('bookings')"
                        @class([
                            'px-6 py-2.5 text-[11px] font-bold uppercase tracking-widest rounded-lg transition-all outline-none',
                            'bg-base-100 text-[#F96015] shadow-sm' => $activeTab === 'bookings',
                            'text-base-content/50 hover:text-base-content' => $activeTab !== 'bookings'
                        ])
                    >
                        {{ __('Booking History') }}
                    </button>
                    <button 
                        wire:click="setTab('payments')"
                        @class([
                            'px-6 py-2.5 text-[11px] font-bold uppercase tracking-widest rounded-lg transition-all outline-none',
                            'bg-base-100 text-[#F96015] shadow-sm' => $activeTab === 'payments',
                            'text-base-content/50 hover:text-base-content' => $activeTab !== 'payments'
                        ])
                    >
                        {{ __('Payment Records') }}
                    </button>
                    <button 
                        wire:click="setTab('activity')"
                        @class([
                            'px-6 py-2.5 text-[11px] font-bold uppercase tracking-widest rounded-lg transition-all outline-none',
                            'bg-base-100 text-[#F96015] shadow-sm' => $activeTab === 'activity',
                            'text-base-content/50 hover:text-base-content' => $activeTab !== 'activity'
                        ])
                    >
                        {{ __('Activity Log') }}
                    </button>
                </nav>

                <!-- Tab Content -->
                <div class="animate-in fade-in slide-in-from-bottom-2 duration-700">
                    @if($activeTab === 'bookings')
                        <div class="space-y-6">
                            <x-ui.table search="searchBookings">
                                <x-slot:filters>
                                    <select wire:model.live="filterBookingStatus" class="select select-sm bg-base-200 border-base-content/10 text-[11px] font-bold uppercase tracking-widest rounded-lg focus:ring-dp-rose focus:border-dp-rose transition-all h-9">
                                        <option value="">{{ __('All Statuses') }}</option>
                                        @foreach(\App\Enums\BookingStatus::cases() as $status)
                                            <option value="{{ $status->value }}">{{ str($status->value)->headline() }}</option>
                                        @endforeach
                                    </select>
                                </x-slot:filters>

                                <x-slot:header>
                                    <x-ui.table.header class="cursor-pointer group" wire:click="sortByBookings('reference')">
                                        <div class="flex items-center gap-2">
                                            {{ __('Reference') }}
                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-3.5 h-3.5 opacity-20 group-hover:opacity-100 transition-opacity {{ $sortBookingsField === 'reference' ? 'opacity-100 text-[#F96015]' : '' }}">
                                                <path fill-rule="evenodd" d="M10 3a.75.75 0 01.55.24l3.25 3.5a.75.75 0 11-1.1 1.02L10 4.852 7.3 7.76a.75.75 0 01-1.1-1.02l3.25-3.5A.75.75 0 0110 3zm-3.75 9.25a.75.75 0 111.1 1.02L10 15.148l2.7-2.908a.75.75 0 111.1 1.02l-3.25 3.5a.75.75 0 01-1.1 0l-3.25-3.5z" clip-rule="evenodd" />
                                            </svg>
                                        </div>
                                    </x-ui.table.header>
                                    <x-ui.table.header>{{ __('Service Details') }}</x-ui.table.header>
                                    <x-ui.table.header class="cursor-pointer group" wire:click="sortByBookings('event_date')">
                                        <div class="flex items-center gap-2">
                                            {{ __('Event Date') }}
                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-3.5 h-3.5 opacity-20 group-hover:opacity-100 transition-opacity {{ $sortBookingsField === 'event_date' ? 'opacity-100 text-[#F96015]' : '' }}">
                                                <path fill-rule="evenodd" d="M10 3a.75.75 0 01.55.24l3.25 3.5a.75.75 0 11-1.1 1.02L10 4.852 7.3 7.76a.75.75 0 01-1.1-1.02l3.25-3.5A.75.75 0 0110 3zm-3.75 9.25a.75.75 0 111.1 1.02L10 15.148l2.7-2.908a.75.75 0 111.1 1.02l-3.25 3.5a.75.75 0 01-1.1 0l-3.25-3.5z" clip-rule="evenodd" />
                                            </svg>
                                        </div>
                                    </x-ui.table.header>
                                    <x-ui.table.header class="cursor-pointer group" wire:click="sortByBookings('status')">
                                        <div class="flex items-center gap-2">
                                            {{ __('Status') }}
                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-3.5 h-3.5 opacity-20 group-hover:opacity-100 transition-opacity {{ $sortBookingsField === 'status' ? 'opacity-100 text-[#F96015]' : '' }}">
                                                <path fill-rule="evenodd" d="M10 3a.75.75 0 01.55.24l3.25 3.5a.75.75 0 11-1.1 1.02L10 4.852 7.3 7.76a.75.75 0 01-1.1-1.02l3.25-3.5A.75.75 0 0110 3zm-3.75 9.25a.75.75 0 111.1 1.02L10 15.148l2.7-2.908a.75.75 0 111.1 1.02l-3.25 3.5a.75.75 0 01-1.1 0l-3.25-3.5z" clip-rule="evenodd" />
                                            </svg>
                                        </div>
                                    </x-ui.table.header>
                                    <x-ui.table.header class="text-right cursor-pointer group" wire:click="sortByBookings('total_amount')">
                                        <div class="flex items-center justify-end gap-2">
                                            {{ __('Total') }}
                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-3.5 h-3.5 opacity-20 group-hover:opacity-100 transition-opacity {{ $sortBookingsField === 'total_amount' ? 'opacity-100 text-[#F96015]' : '' }}">
                                                <path fill-rule="evenodd" d="M10 3a.75.75 0 01.55.24l3.25 3.5a.75.75 0 11-1.1 1.02L10 4.852 7.3 7.76a.75.75 0 01-1.1-1.02l3.25-3.5A.75.75 0 0110 3zm-3.75 9.25a.75.75 0 111.1 1.02L10 15.148l2.7-2.908a.75.75 0 111.1 1.02l-3.25 3.5a.75.75 0 01-1.1 0l-3.25-3.5z" clip-rule="evenodd" />
                                            </svg>
                                        </div>
                                    </x-ui.table.header>
                                </x-slot:header>
                                
                                <tbody>
                                    @forelse ($bookings as $booking)
                                        <x-ui.table.row wire:key="booking-{{ $booking->id }}" class="group">
                                            <x-ui.table.cell>
                                                <a href="{{ route('admin.bookings.show', $booking->reference) }}" wire:navigate class="font-mono text-[11px] font-black text-[#F96015] bg-[#F96015]/5 px-3 py-1.5 rounded border border-[#F96015]/10 hover:bg-[#F96015] hover:text-white transition-all uppercase tracking-widest">
                                                    {{ $booking->reference }}
                                                </a>
                                            </x-ui.table.cell>
                                            
                                            <x-ui.table.cell>
                                                <div class="space-y-1">
                                                    <div class="text-[14px] font-bold text-base-content leading-tight group-hover:text-[#F96015] transition-colors">
                                                        {{ $booking->items->pluck('package.name')->implode(', ') ?: __('No Packages') }}
                                                    </div>
                                                    <div class="text-[10px] text-base-content/40 font-bold uppercase tracking-widest">
                                                        {{ $booking->event_type ? \Illuminate\Support\Str::headline($booking->event_type->value) : __('Custom Event') }}
                                                    </div>
                                                </div>
                                            </x-ui.table.cell>
                                            
                                            <x-ui.table.cell>
                                                <div class="flex items-center gap-2.5">
                                                    <div class="w-1.5 h-1.5 rounded-full bg-base-300"></div>
                                                    <span class="text-[13px] font-bold text-base-content">
                                                        {{ $booking->event_date ? $booking->event_date->format('M d, Y') : __('No Date') }}
                                                    </span>
                                                </div>
                                            </x-ui.table.cell>
                                            
                                            <x-ui.table.cell>
                                                <x-ui.badge :type="$booking->status?->value ?? 'pending'" dot class="font-bold text-[11px] uppercase tracking-wide">
                                                    {{ \Illuminate\Support\Str::headline($booking->status?->value ?? 'pending') }}
                                                </x-ui.badge>
                                            </x-ui.table.cell>
                                            
                                            <x-ui.table.cell class="text-right">
                                                <span class="text-[15px] font-bold text-base-content tracking-tight">GH₵{{ number_format($booking->total_amount, 2) }}</span>
                                            </x-ui.table.cell>
                                        </x-ui.table.row>
                                    @empty
                                        <x-ui.table.row>
                                            <x-ui.table.cell colspan="5" class="py-24 text-center">
                                                <div class="flex flex-col items-center justify-center opacity-20">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-16 h-16 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z" />
                                                    </svg>
                                                    <p class="text-[14px] font-bold uppercase tracking-widest text-[#F96015]">{{ __('No history found matching your criteria.') }}</p>
                                                </div>
                                            </x-ui.table.cell>
                                        </x-ui.table.row>
                                    @endforelse
                                </tbody>

                                <x-slot:pagination>
                                    {{ $bookings->links() }}
                                </x-slot:pagination>
                            </x-ui.table>
                        </div>
                    @elseif($activeTab === 'payments')
                        <div class="space-y-6">
                            <x-ui.table search="searchPayments">
                                <x-slot:filters>
                                    <select wire:model.live="filterPaymentStatus" class="select select-sm bg-base-200 border-base-content/10 text-[11px] font-bold uppercase tracking-widest rounded-lg focus:ring-dp-rose focus:border-dp-rose transition-all h-9">
                                        <option value="">{{ __('All Statuses') }}</option>
                                        @foreach(\App\Enums\PaymentGatewayStatus::cases() as $status)
                                            <option value="{{ $status->value }}">{{ str($status->value)->headline() }}</option>
                                        @endforeach
                                    </select>
                                </x-slot:filters>

                                <x-slot:header>
                                    <x-ui.table.header class="cursor-pointer group" wire:click="sortByPayments('created_at')">
                                        <div class="flex items-center gap-2">
                                            {{ __('Timeline') }}
                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-3.5 h-3.5 opacity-20 group-hover:opacity-100 transition-opacity {{ $sortPaymentsField === 'created_at' ? 'opacity-100 text-[#F96015]' : '' }}">
                                                <path fill-rule="evenodd" d="M10 3a.75.75 0 01.55.24l3.25 3.5a.75.75 0 11-1.1 1.02L10 4.852 7.3 7.76a.75.75 0 01-1.1-1.02l3.25-3.5A.75.75 0 0110 3zm-3.75 9.25a.75.75 0 111.1 1.02L10 15.148l2.7-2.908a.75.75 0 111.1 1.02l-3.25 3.5a.75.75 0 01-1.1 0l-3.25-3.5z" clip-rule="evenodd" />
                                            </svg>
                                        </div>
                                    </x-ui.table.header>
                                    <x-ui.table.header>{{ __('Gateway & Method') }}</x-ui.table.header>
                                    <x-ui.table.header class="cursor-pointer group" wire:click="sortByPayments('status')">
                                        <div class="flex items-center gap-2">
                                            {{ __('Status') }}
                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-3.5 h-3.5 opacity-20 group-hover:opacity-100 transition-opacity {{ $sortPaymentsField === 'status' ? 'opacity-100 text-[#F96015]' : '' }}">
                                                <path fill-rule="evenodd" d="M10 3a.75.75 0 01.55.24l3.25 3.5a.75.75 0 11-1.1 1.02L10 4.852 7.3 7.76a.75.75 0 01-1.1-1.02l3.25-3.5A.75.75 0 0110 3zm-3.75 9.25a.75.75 0 111.1 1.02L10 15.148l2.7-2.908a.75.75 0 111.1 1.02l-3.25 3.5a.75.75 0 01-1.1 0l-3.25-3.5z" clip-rule="evenodd" />
                                            </svg>
                                        </div>
                                    </x-ui.table.header>
                                    <x-ui.table.header class="text-right cursor-pointer group" wire:click="sortByPayments('amount')">
                                        <div class="flex items-center justify-end gap-2">
                                            {{ __('Amount') }}
                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-3.5 h-3.5 opacity-20 group-hover:opacity-100 transition-opacity {{ $sortPaymentsField === 'amount' ? 'opacity-100 text-[#F96015]' : '' }}">
                                                <path fill-rule="evenodd" d="M10 3a.75.75 0 01.55.24l3.25 3.5a.75.75 0 11-1.1 1.02L10 4.852 7.3 7.76a.75.75 0 01-1.1-1.02l3.25-3.5A.75.75 0 0110 3zm-3.75 9.25a.75.75 0 111.1 1.02L10 15.148l2.7-2.908a.75.75 0 111.1 1.02l-3.25 3.5a.75.75 0 01-1.1 0l-3.25-3.5z" clip-rule="evenodd" />
                                            </svg>
                                        </div>
                                    </x-ui.table.header>
                                </x-slot:header>
                                
                                <tbody>
                                    @forelse ($payments as $payment)
                                        <x-ui.table.row wire:key="payment-{{ $payment->id }}">
                                            <x-ui.table.cell>
                                                <div class="flex flex-col">
                                                    <span class="text-[13px] font-bold text-base-content">{{ $payment->created_at->format('M d, Y') }}</span>
                                                    <span class="text-[11px] font-bold text-base-content/40">{{ $payment->created_at->format('H:i') }}</span>
                                                </div>
                                            </x-ui.table.cell>
                                            <x-ui.table.cell>
                                                <div class="flex items-center gap-3">
                                                    <div class="text-[11px] font-bold text-base-content/40 uppercase tracking-widest">{{ $payment->gateway?->value ?: 'Automatic' }}</div>
                                                    <div class="w-1.5 h-1.5 rounded-full bg-base-300"></div>
                                                    <div class="text-[11px] font-bold text-base-content/60 uppercase tracking-widest">{{ $payment->method?->value ?: 'System' }}</div>
                                                </div>
                                            </x-ui.table.cell>
                                            <x-ui.table.cell>
                                                <x-ui.badge :type="$payment->status?->value ?? 'pending'" dot class="font-bold text-[11px] uppercase tracking-wide">
                                                    {{ str($payment->status?->value ?? 'Pending')->title() }}
                                                </x-ui.badge>
                                            </x-ui.table.cell>
                                            <x-ui.table.cell class="text-right">
                                                <span class="text-[15px] font-bold text-base-content tracking-tight">GH₵{{ number_format($payment->amount, 2) }}</span>
                                            </x-ui.table.cell>
                                        </x-ui.table.row>
                                    @empty
                                        <x-ui.table.row>
                                            <x-ui.table.cell colspan="4" class="py-24 text-center">
                                                <div class="opacity-20 flex flex-col items-center">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-16 h-16 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2-2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                                                    </svg>
                                                    <p class="text-[14px] font-bold uppercase tracking-widest text-[#F96015]">{{ __('No transactions found matching your criteria.') }}</p>
                                                </div>
                                            </x-ui.table.cell>
                                        </x-ui.table.row>
                                    @endforelse
                                </tbody>

                                <x-slot:pagination>
                                    {{ $payments->links() }}
                                </x-slot:pagination>
                            </x-ui.table>
                        </div>
                    @elseif($activeTab === 'activity')
                        <div class="space-y-6">
                            <div class="bg-base-200/50 rounded-xl p-4 border border-base-content/5 mb-6">
                                <div class="relative">
                                    <span class="absolute inset-y-0 left-3 flex items-center text-base-content/40 pointer-events-none">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                        </svg>
                                    </span>
                                    <input 
                                        wire:model.live.debounce.300ms="searchActivity" 
                                        type="text" 
                                        placeholder="{{ __('Search activities...') }}"
                                        class="w-full pl-9 pr-4 py-2 bg-base-100 border border-base-content/10 rounded-lg text-sm text-base-content focus:ring-2 focus:ring-[#F96015]/20 focus:border-[#F96015] outline-none transition-all placeholder:text-base-content/40"
                                    >
                                </div>
                            </div>

                            <div class="relative pl-8 space-y-12 before:absolute before:left-[11px] before:top-2 before:bottom-2 before:w-0.5 before:bg-base-300 transition-all">
                                @forelse ($activities as $activity)
                                    <div class="relative group">
                                        <div class="absolute -left-[35px] top-1.5 w-7 h-7 rounded-lg bg-base-100 border border-base-content/10 flex items-center justify-center z-10 shadow-sm transition-all group-hover:border-[#F96015]/30 group-hover:shadow-lg group-hover:shadow-[#F96015]/10">
                                            <div class="w-1.5 h-1.5 rounded-full bg-[#F96015]/20 group-hover:bg-[#F96015] transition-colors"></div>
                                        </div>
                                        
                                        <div class="space-y-4 pt-0.5">
                                            <div class="flex items-center justify-between">
                                                <div class="flex items-center gap-4">
                                                    <span class="text-[15px] font-bold text-base-content leading-none group-hover:text-[#F96015] transition-colors">
                                                        {{ \Illuminate\Support\Str::headline($activity->description) }}
                                                    </span>
                                                    <x-ui.badge type="ghost" class="text-[9px] font-black uppercase tracking-widest text-base-content/40 flex items-center gap-1.5">
                                                        <div class="w-1 h-1 rounded-full bg-current opacity-40"></div>
                                                        {{ $activity->causer?->name ?? __('System') }}
                                                    </x-ui.badge>
                                                </div>
                                                <time class="text-[11px] font-bold text-base-content/25 uppercase tracking-widest">{{ $activity->created_at->format('M d, H:i') }}</time>
                                            </div>

                                            @if($activity->properties && count($activity->properties))
                                                <div x-data="{ open: false }" class="bg-base-300/20 rounded-lg border border-base-content/5 overflow-hidden transition-all group-hover:border-[#F96015]/10">
                                                    <button @click="open = !open" class="w-full px-5 py-3.5 flex items-center justify-between text-[10px] font-black uppercase tracking-[0.2em] text-base-content/40 hover:bg-[#F96015]/5 transition-colors">
                                                        <div class="flex items-center gap-2.5">
                                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 opacity-50" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4" />
                                                            </svg>
                                                            {{ __('Technical Payload') }}
                                                        </div>
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-3 h-3 transition-transform duration-300" :class="open ? 'rotate-180 text-[#F96015]' : ''" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M19 9l-7 7-7-7" />
                                                        </svg>
                                                    </button>
                                                    
                                                    <div x-show="open" x-collapse>
                                                        <div class="p-5 pt-0">
                                                            <pre class="text-[12px] font-mono p-4 bg-neutral text-white/90 rounded-lg overflow-x-auto selection:bg-[#F96015]/40 no-scrollbar"><code>{{ json_encode($activity->properties, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) }}</code></pre>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                @empty
                                    <div class="py-24 text-center opacity-20">
                                        <div class="flex flex-col items-center">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-16 h-16 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                            <p class="text-[14px] font-bold uppercase tracking-widest">{{ __('Archive Empty') }}</p>
                                        </div>
                                    </div>
                                @endforelse
                            </div>

                            @if($activities->hasPages())
                                <div class="mt-10 pt-6 border-t border-base-content/5">
                                    {{ $activities->links() }}
                                </div>
                            @endif
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Sidebar (Right 1/3) -->
        <div class="space-y-8">
            <!-- Contact Details Card -->
            <x-ui.card>
                <div class="flex items-center gap-2.5 mb-6">
                    <div class="w-8 h-8 rounded-xl bg-[#F96015]/10 text-[#F96015] flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                    </div>
                    <h2 class="text-[11px] font-black uppercase tracking-[0.2em] text-base-content">{{ __('Contact Details') }}</h2>
                </div>
                
                <div class="space-y-5">
                    <div class="group">
                        <p class="text-[10px] font-bold text-base-content/40 uppercase tracking-widest mb-1.5">{{ __('Email Address') }}</p>
                        <a href="mailto:{{ $customer->email }}" class="flex items-center gap-3 text-[14px] font-bold text-base-content group-hover:text-[#F96015] transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 opacity-30" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                            </svg>
                            {{ $customer->email }}
                        </a>
                    </div>

                    <div class="group">
                        <p class="text-[10px] font-bold text-base-content/40 uppercase tracking-widest mb-1.5">{{ __('Phone Number') }}</p>
                        <a href="tel:{{ $customer->phone }}" class="flex items-center gap-3 text-[14px] font-bold text-base-content group-hover:text-[#F96015] transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 opacity-30" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h2.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                            </svg>
                            {{ $customer->phone }}
                        </a>
                    </div>
                </div>
            </x-ui.card>

            <!-- Account Life Timeline Card -->
            <div class="relative rounded-xl p-[1px] shadow-sm bg-gradient-to-br from-base-content/5 to-transparent">
                <div class="p-6 rounded-[calc(1.5rem-1px)] bg-base-100 h-full w-full relative overflow-hidden">
                    <h3 class="text-[10px] font-black uppercase tracking-[0.2em] text-base-content/40 mb-6">{{ __('Timeline') }}</h3>
                    
                    <div class="space-y-6 relative z-10">
                        <div class="flex flex-col gap-1.5 border-l-2 border-[#F96015]/30 pl-4">
                            <span class="text-[10px] font-bold text-base-content/40 uppercase tracking-widest">{{ __('Joined') }}</span>
                            <span class="text-[13px] font-bold text-base-content">{{ $customer->created_at->format('M d, Y') }} <span class="text-[11px] text-base-content/40 font-medium ml-1">({{ $customer->created_at->diffForHumans() }})</span></span>
                        </div>
                        
                        <div class="flex flex-col gap-1.5 border-l-2 border-[#FFC926]/30 pl-4">
                            <span class="text-[10px] font-bold text-base-content/40 uppercase tracking-widest">{{ __('Last Activity') }}</span>
                            @if($booking = $customer->bookings()->latest()->first())
                                <span class="text-[13px] font-bold text-base-content">{{ $booking->created_at?->format('M d, H:i') }} <span class="text-[11px] text-base-content/40 font-medium ml-1">({{ $booking->created_at?->diffForHumans() }})</span></span>
                            @else
                                <span class="text-[13px] font-bold text-base-content">{{ __('No activity yet') }}</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
