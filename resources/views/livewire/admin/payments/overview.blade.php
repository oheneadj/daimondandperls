<div class="space-y-6 pb-10">
    {{-- Page Header --}}
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-6">
        <div>
            <h1 class="text-[28px] font-semibold text-base-content leading-tight">
                {{ __('Payments Overview') }}
            </h1>
            <p class="text-[14px] text-base-content/50 mt-1">{{ __('Manage and verify your capital inflows.') }}</p>
        </div>
    </div>

    {{-- Stats Bar --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div class="bg-white border border-base-content/5 rounded-lg p-4 flex items-center gap-4">
            <div class="w-10 h-10 rounded-lg bg-[#9ABC05]/10 flex items-center justify-center">
                @include('layouts.partials.icons.check-circle-solid', ['class' => 'w-5 h-5 text-[#9ABC05]'])
            </div>
            <div>
                <p class="text-[20px] font-bold text-base-content">GH₵ {{ number_format($stats['total_received'], 2) }}</p>
                <p class="text-[10px] font-bold uppercase tracking-widest text-base-content/40">{{ __('Total Received') }}</p>
            </div>
        </div>
        <div class="bg-white border border-base-content/5 rounded-lg p-4 flex items-center gap-4">
            <div class="w-10 h-10 rounded-lg bg-[#FFC926]/10 flex items-center justify-center">
                @include('layouts.partials.icons.information-circle-solid', ['class' => 'w-5 h-5 text-[#FFC926]'])
            </div>
            <div>
                <p class="text-[20px] font-bold text-base-content">{{ number_format($stats['awaiting_payment']) }}</p>
                <p class="text-[10px] font-bold uppercase tracking-widest text-base-content/40">{{ __('Awaiting Payment') }}</p>
            </div>
        </div>
        <div class="bg-white border border-base-content/5 rounded-lg p-4 flex items-center gap-4">
            <div class="w-10 h-10 rounded-lg bg-[#D52518]/10 flex items-center justify-center">
                @include('layouts.partials.icons.exclamation-triangle-solid', ['class' => 'w-5 h-5 text-[#D52518]'])
            </div>
            <div>
                <p class="text-[20px] font-bold text-base-content">{{ number_format($stats['failed_transactions']) }}</p>
                <p class="text-[10px] font-bold uppercase tracking-widest text-base-content/40">{{ __('Failed Transactions') }}</p>
            </div>
        </div>
    </div>

    {{-- Table --}}
    <x-ui.table search="search">
        <x-slot name="filters">
            <div class="flex flex-wrap items-center gap-3">
                <select wire:model.live="activeTab" class="bg-base-200 border-none text-[13px] rounded-lg px-3 py-2 focus:ring-2 focus:ring-[#F96015]/30 outline-none transition-all font-medium">
                    <option value="all">{{ __('All Payments') }}</option>
                    <option value="paid">{{ __('Paid Only') }}</option>
                    <option value="pending">{{ __('Pending Only') }}</option>
                    <option value="failed">{{ __('Failed Only') }}</option>
                </select>
            </div>
        </x-slot>

        <x-slot name="header">
            <x-ui.table.th sortable="booking_id" :direction="$sortField === 'booking_id' ? $sortDirection : null">Ref</x-ui.table.th>
            <x-ui.table.th>Customer</x-ui.table.th>
            <x-ui.table.th sortable="amount" :direction="$sortField === 'amount' ? $sortDirection : null">Amount</x-ui.table.th>
            <x-ui.table.th>Method</x-ui.table.th>
            <x-ui.table.th sortable="created_at" :direction="$sortField === 'created_at' ? $sortDirection : null">Date</x-ui.table.th>
            <x-ui.table.th sortable="status" :direction="$sortField === 'status' ? $sortDirection : null" align="center">Status</x-ui.table.th>
            <x-ui.table.th align="right">Actions</x-ui.table.th>
        </x-slot>

        <tbody>
            @forelse($payments as $payment)
                <x-ui.table.row wire:key="payment-{{ $payment->id }}" class="border-b border-base-content/5 last:border-0">
                    <x-ui.table.td>
                        <a href="{{ route('admin.bookings.show', $payment->booking) }}" wire:navigate>
                            <span class="inline-flex items-center px-2.5 py-1 rounded-lg bg-base-200 text-base-content/70 text-[11px] font-bold tracking-wide hover:bg-base-300 transition-colors uppercase">
                                {{ $payment->booking->reference }}
                            </span>
                        </a>
                    </x-ui.table.td>
                    
                    <x-ui.table.td>
                        <div class="flex flex-col">
                            <span class="text-[13px] font-semibold text-base-content hover:text-[#F96015] transition-colors cursor-pointer">
                                {{ $payment->booking->customer->name }}
                            </span>
                        </div>
                    </x-ui.table.td>
                    
                    <x-ui.table.td>
                        <span class="text-[14px] font-bold text-base-content">
                            GH₵ {{ number_format($payment->amount, 2) }}
                        </span>
                    </x-ui.table.td>
                    
                    <x-ui.table.td>
                        <div class="flex items-center gap-2">
                            @php
                                $methodIcon = match($payment->method?->value) {
                                    'mobile_money' => '<svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 text-base-content/40" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z" /></svg>',
                                    'card' => '<svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 text-base-content/40" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" /></svg>',
                                    default => '<svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 text-base-content/40" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" /></svg>',
                                };
                            @endphp
                            <div class="w-6 h-6 rounded-md bg-base-200 flex items-center justify-center shrink-0">
                                {!! $methodIcon !!}
                            </div>
                            <span class="text-[12px] text-base-content font-medium capitalize">{{ str_replace('_', ' ', $payment->method?->value ?? 'Manual') }}</span>
                        </div>
                    </x-ui.table.td>
                    
                    <x-ui.table.td>
                        <div class="flex items-center gap-2">
                            <div class="w-6 h-6 rounded-md bg-base-200 flex items-center justify-center shrink-0">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 text-base-content/40" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            </div>
                            <span class="text-[12px] text-base-content font-medium">
                                {{ $payment->created_at->format('d M, Y') }}
                            </span>
                        </div>
                    </x-ui.table.td>
                    
                    <x-ui.table.td align="center">
                        @php
                            $paymentStatus = match($payment->status?->value) {
                                'successful' => ['color' => 'text-success', 'label' => 'Paid'],
                                'pending' => ['color' => 'text-warning', 'label' => 'Pending'],
                                'failed' => ['color' => 'text-error', 'label' => 'Failed'],
                                default => ['color' => 'text-base-content/40', 'label' => 'Unknown']
                            };
                        @endphp
                        <div class="inline-flex items-center gap-1.5 {{ $paymentStatus['color'] }} text-[11px] font-bold uppercase tracking-wide">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-3 h-3 opacity-50" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            {{ $paymentStatus['label'] }}
                        </div>
                    </x-ui.table.td>
                    
                    <x-ui.table.td align="right">
                        <div class="flex items-center justify-end gap-2">
                            @if($payment->status?->value === 'pending' && $payment->gateway?->value === 'manual')
                                <button wire:click="confirmVerify({{ $payment->id }})" wire:loading.attr="disabled" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-[#FFC926]/10 text-black text-[12px] font-bold hover:bg-[#FFC926]/20 transition-colors">
                                    <svg wire:loading.remove wire:target="confirmVerify({{ $payment->id }})" xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" /></svg>
                                    <span wire:loading wire:target="confirmVerify({{ $payment->id }})" class="loading loading-spinner loading-xs"></span>
                                    {{ __('Verify') }}
                                </button>
                            @else
                                @if($payment->status?->value === 'successful')
                                    <a href="{!! app(\App\Services\InvoiceService::class)->getDownloadUrl($payment->booking) !!}" target="_blank" class="flex gap-1 p-1.5 rounded-lg bg-base-200 text-base-content/60 hover:text-[#F96015] hover:bg-[#F96015]/10 transition-colors" title="{{ __('Download Invoice') }}">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                        </svg>
                                         Invoice
                                    </a>
                                @endif
                                <button wire:click="showPayment({{ $payment->id }})" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-[#18542A]/10 text-[#18542A] text-[12px] font-bold hover:bg-[#18542A]/20 transition-colors">
                                    @include('layouts.partials.icons.eye', ['class' => 'w-3.5 h-3.5'])
                                    {{ __('View') }}
                                </button>
                            @endif
                        </div>
                    </x-ui.table.td>
                </x-ui.table.row>
            @empty
                <x-ui.table.empty colspan="7" />
            @endforelse
        </tbody>

        <x-slot name="pagination">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div class="text-[12px] text-base-content/40 font-medium">
                    Showing {{ $payments->firstItem() ?? 0 }} to {{ $payments->lastItem() ?? 0 }} of {{ $payments->total() }} payments
                </div>
                <div class="flex items-center justify-end gap-2">
                    {{ $payments->links(data: ['scrollTo' => false]) }}
                </div>
            </div>
        </x-slot>
    </x-ui.table>

    <!-- Verification Modal -->
    <x-ui.modal wire:model="showingVerifyModal" title="Verify Payment" maxWidth="sm">
        <div class="text-center space-y-4">
            <div class="w-16 h-16 bg-success/10 text-success rounded-full flex items-center justify-center mx-auto">
                @include('layouts.partials.icons.check-circle-solid', ['class' => 'w-8 h-8'])
            </div>
            <p class="text-[15px] text-base-content">
                Are you sure you want to verify this payment for booking <strong class="text-primary">#{{ $paymentToVerify?->booking?->reference }}</strong>?
            </p>
            <p class="text-[13px] text-base-content/60">
                This will mark the payment as successful and update the booking status.
            </p>
        </div>

        <x-slot name="footer" class="!justify-center gap-3">
            <x-ui.button variant="secondary" @click="show = false">{{ __('Cancel') }}</x-ui.button>
            <x-ui.button variant="primary" wire:click="verifyPayment">
                {{ __('Confirm Verification') }}
            </x-ui.button>
        </x-slot>
    </x-ui.modal>

    {{-- Payment Detail Modal --}}
    <x-ui.modal wire:model="showingPaymentModal" title="Payment Details">
        <div class="space-y-8">
            @if($selectedPayment)
                <div class="flex flex-col items-center justify-center text-center space-y-2 py-4">
                    <div class="w-16 h-16 rounded-full bg-base-200 flex items-center justify-center mb-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 text-base-content/20" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <h2 class="text-3xl font-bold text-base-content tracking-tighter">
                        GH₵ {{ number_format($selectedPayment->amount, 2) }}
                    </h2>
                    @php
                        $paymentStatus = match($selectedPayment->status?->value) {
                            'successful' => ['color' => 'text-success', 'label' => 'Paid'],
                            'pending' => ['color' => 'text-warning', 'label' => 'Pending'],
                            'failed' => ['color' => 'text-error', 'label' => 'Failed'],
                            default => ['color' => 'text-base-content/40', 'label' => 'Unknown']
                        };
                    @endphp
                    <div class="inline-flex items-center gap-1.5 {{ $paymentStatus['color'] }} text-[11px] font-bold uppercase tracking-[0.2em]">
                        <span class="w-1.5 h-1.5 rounded-full bg-current"></span>
                        {{ $paymentStatus['label'] }}
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-y-6 gap-x-12 px-2">
                    <div class="space-y-1">
                        <p class="text-[10px] font-bold uppercase tracking-widest text-base-content/40">{{ __('Booking Ref') }}</p>
                        <p class="text-[13px] font-mono font-bold text-primary">#{{ $selectedPayment->booking->reference }}</p>
                    </div>
                    <div class="space-y-1">
                        <p class="text-[10px] font-bold uppercase tracking-widest text-base-content/40">{{ __('Transaction Date') }}</p>
                        <p class="text-[13px] font-bold text-base-content">{{ $selectedPayment->created_at->format('d M, Y H:i') }}</p>
                    </div>
                    <div class="space-y-1">
                        <p class="text-[10px] font-bold uppercase tracking-widest text-base-content/40">{{ __('Payment Method') }}</p>
                        <p class="text-[13px] font-bold text-base-content capitalize">{{ str_replace('_', ' ', $selectedPayment->method?->value ?? 'N/A') }}</p>
                    </div>
                    <div class="space-y-1">
                        <p class="text-[10px] font-bold uppercase tracking-widest text-base-content/40">{{ __('Gateway') }}</p>
                        <p class="text-[13px] font-bold text-base-content uppercase">{{ $selectedPayment->gateway?->value }}</p>
                    </div>
                    <div class="col-span-2 space-y-1">
                        <p class="text-[10px] font-bold uppercase tracking-widest text-base-content/40">{{ __('Gateway Reference') }}</p>
                        <p class="text-[13px] font-mono font-bold text-base-content break-all bg-base-100 p-2 rounded-lg border border-base-content/5 leading-relaxed">{{ $selectedPayment->gateway_reference ?? 'No reference available' }}</p>
                    </div>
                    @if($selectedPayment->verifiedBy)
                        <div class="col-span-2 space-y-1">
                            <p class="text-[10px] font-bold uppercase tracking-widest text-base-content/40">{{ __('Verified By') }}</p>
                            <p class="text-[12px] font-bold text-base-content flex items-center gap-2">
                                <span class="w-6 h-6 rounded-full bg-primary-soft text-primary flex items-center justify-center text-[10px] border border-dp-rose/10">{{ substr($selectedPayment->verifiedBy->name, 0, 1) }}</span>
                                {{ $selectedPayment->verifiedBy->name }} <span class="text-base-content/40 font-medium">at {{ $selectedPayment->verified_at?->format('d M, Y H:i') }}</span>
                            </p>
                        </div>
                    @endif
                </div>

                <div class="flex flex-col gap-3">
                    <a href="{{ route('admin.bookings.show', $selectedPayment->booking) }}" wire:navigate class="w-full inline-flex items-center justify-center gap-2.5 bg-neutral text-white px-7 py-4 rounded-lg font-bold hover:bg-dp-body transition-all shadow-md active:scale-[0.98]">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-white/60" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" /></svg>
                        {{ __('Open Booking #') }}{{ $selectedPayment->booking->reference }}
                    </a>

                    @if($selectedPayment->status?->value === 'successful')
                        <a href="{!! app(\App\Services\InvoiceService::class)->getDownloadUrl($selectedPayment->booking) !!}" target="_blank" class="w-full inline-flex items-center justify-center gap-2.5 bg-secondary text-white px-7 py-4 rounded-lg font-bold hover:bg-dp-green transition-all shadow-md">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-white/60" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            {{ __('Download Official Invoice') }}
                        </a>
                    @endif
                    <button wire:click="closePaymentModal" class="w-full text-[13px] font-bold text-base-content/40 hover:text-base-content transition-colors py-2 tracking-tight">
                        {{ __('Close Details') }}
                    </button>
                </div>
            @endif
        </div>
    </x-ui.modal>
</div>
