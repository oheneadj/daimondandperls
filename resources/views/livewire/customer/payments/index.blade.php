<div class="space-y-6">
    {{-- Page Header --}}
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-6">
        <div>
            <h1 class="text-[28px] font-semibold text-base-content leading-tight">
                {{ __('Payment History') }}
            </h1>
            <p class="text-[14px] text-base-content/50 mt-1">{{ __('View all your transactions and download invoices.') }}</p>
        </div>
    </div>

    {{-- Table --}}
    <x-ui.table search="search">
        <x-slot name="filters">
            <div class="flex flex-wrap items-center gap-3">
                <select wire:model.live="status" class="bg-base-200 border-none text-[13px] rounded-lg px-3 py-2 focus:ring-2 focus:ring-primary/30 outline-none transition-all font-medium">
                    <option value="">All Statuses</option>
                    @foreach(\App\Enums\PaymentGatewayStatus::cases() as $s)
                        <option wire:key="status-{{ $s->value }}" value="{{ $s->value }}">{{ str($s->value)->title()->replace('_', ' ') }}</option>
                    @endforeach
                </select>
            </div>
        </x-slot>

        <x-slot name="header">
            <x-ui.table.th>Booking Ref</x-ui.table.th>
            <x-ui.table.th>Amount</x-ui.table.th>
            <x-ui.table.th>Method</x-ui.table.th>
            <x-ui.table.th align="center">Status</x-ui.table.th>
            <x-ui.table.th>Date</x-ui.table.th>
            <x-ui.table.th align="right">Actions</x-ui.table.th>
        </x-slot>

        @forelse($payments as $payment)
            <x-ui.table.row wire:key="payment-{{ $payment->id }}">
                <x-ui.table.td>
                    <a href="{{ route('dashboard.bookings.show', $payment->booking->reference) }}" wire:navigate>
                        <span class="inline-flex items-center px-2.5 py-1 rounded-lg bg-base-200 text-base-content/70 text-[11px] font-bold tracking-wide hover:bg-base-300 transition-colors">
                            {{ $payment->booking->reference }}
                        </span>
                    </a>
                </x-ui.table.td>
                <x-ui.table.td>
                    <span class="text-[14px] font-bold text-base-content">GH&#8373; {{ number_format($payment->amount, 2) }}</span>
                </x-ui.table.td>
                <x-ui.table.td>
                    <span class="text-[13px] font-medium text-base-content/70">{{ $payment->method?->name ?? '-' }}</span>
                </x-ui.table.td>
                <x-ui.table.td align="center">
                    @php
                        $statusType = match($payment->status) {
                            \App\Enums\PaymentGatewayStatus::Successful => 'paid',
                            \App\Enums\PaymentGatewayStatus::Failed => 'failed',
                            \App\Enums\PaymentGatewayStatus::Pending => 'pending',
                            default => 'default',
                        };
                    @endphp
                    <x-badge :type="$statusType">
                        {{ $payment->status->name }}
                    </x-badge>
                </x-ui.table.td>
                <x-ui.table.td>
                    <span class="text-[13px] text-base-content/60 font-medium">
                        {{ $payment->paid_at ? \Carbon\Carbon::parse($payment->paid_at)->format('M j, Y') : $payment->created_at->format('M j, Y') }}
                    </span>
                </x-ui.table.td>
                <x-ui.table.td align="right">
                    <div class="flex items-center justify-end gap-2">
                        {{-- View Details --}}
                        <button wire:click="viewPayment({{ $payment->id }})" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-[#18542A]/10 text-[#18542A] text-[12px] font-bold hover:bg-[#18542A]/20 transition-colors">
                            @include('layouts.partials.icons.eye', ['class' => 'w-3.5 h-3.5'])
                            View
                        </button>

                        {{-- Download Invoice --}}
                        @if($payment->status === \App\Enums\PaymentGatewayStatus::Successful)
                            <a href="{!! app(\App\Services\InvoiceService::class)->getDownloadUrl($payment->booking) !!}" target="_blank" class="inline-flex items-center gap-1 px-2 py-1.5 rounded-lg bg-base-200 text-base-content/40 text-[12px] font-bold hover:bg-base-300 transition-colors" title="Download Invoice">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                            </a>
                        @endif
                    </div>
                </x-ui.table.td>
            </x-ui.table.row>
        @empty
            <x-ui.table.empty colspan="6" title="No payments found" :description="$status ? 'No payments match this filter.' : 'You don\'t have any payment records yet.'" />
        @endforelse

        <x-slot name="pagination">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div class="text-[12px] text-base-content/40 font-medium">
                    {{ __('Navigate through your payment history') }}
                </div>
                <div class="flex items-center justify-end gap-2">
                    {{ $payments instanceof \Illuminate\Contracts\Pagination\Paginator ? $payments->links(data: ['scrollTo' => false]) : '' }}
                </div>
            </div>
        </x-slot>
    </x-ui.table>

    {{-- Payment Details Modal --}}
    @if($selectedPayment)
        <div
            x-data="{ open: true }"
            x-show="open"
            x-on:keydown.escape.window="$wire.closeModal()"
            class="fixed inset-0 z-50 flex items-center justify-center"
        >
            {{-- Backdrop --}}
            <div x-show="open" x-transition.opacity @click="$wire.closeModal()" class="fixed inset-0 bg-black/50 backdrop-blur-sm"></div>

            {{-- Modal Content --}}
            <div
                x-show="open"
                x-transition:enter="transition ease-out duration-200"
                x-transition:enter-start="opacity-0 scale-95"
                x-transition:enter-end="opacity-100 scale-100"
                x-transition:leave="transition ease-in duration-150"
                x-transition:leave-start="opacity-100 scale-100"
                x-transition:leave-end="opacity-0 scale-95"
                class="relative bg-white rounded-2xl shadow-2xl w-full max-w-lg mx-4 max-h-[85vh] overflow-y-auto z-10"
            >
                {{-- Modal Header --}}
                <div class="flex items-center justify-between p-6 border-b border-base-content/10">
                    <div>
                        <h2 class="text-lg font-semibold text-base-content">Payment Details</h2>
                        <p class="text-[12px] text-base-content/50 mt-0.5">{{ $selectedPayment->booking->reference }}</p>
                    </div>
                    <button wire:click="closeModal" class="p-2 hover:bg-base-200 rounded-lg transition-colors text-base-content/40 hover:text-base-content">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                {{-- Modal Body --}}
                <div class="p-6 space-y-6">
                    {{-- Amount & Status --}}
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-[10px] font-bold uppercase tracking-widest text-base-content/40 mb-1">Amount</p>
                            <p class="text-2xl font-bold text-base-content">GH&#8373; {{ number_format($selectedPayment->amount, 2) }}</p>
                        </div>
                        @php
                            $modalStatusType = match($selectedPayment->status) {
                                \App\Enums\PaymentGatewayStatus::Successful => 'paid',
                                \App\Enums\PaymentGatewayStatus::Failed => 'failed',
                                \App\Enums\PaymentGatewayStatus::Pending => 'pending',
                                default => 'default',
                            };
                        @endphp
                        <x-badge :type="$modalStatusType">
                            {{ $selectedPayment->status->name }}
                        </x-badge>
                    </div>

                    {{-- Details Grid --}}
                    <div class="grid grid-cols-2 gap-4">
                        <div class="bg-base-200/50 rounded-xl p-4">
                            <p class="text-[10px] font-bold uppercase tracking-widest text-base-content/40 mb-1">Method</p>
                            <p class="text-[14px] font-semibold text-base-content">{{ $selectedPayment->method?->name ?? '-' }}</p>
                        </div>
                        <div class="bg-base-200/50 rounded-xl p-4">
                            <p class="text-[10px] font-bold uppercase tracking-widest text-base-content/40 mb-1">Gateway</p>
                            <p class="text-[14px] font-semibold text-base-content">{{ $selectedPayment->gateway?->name ?? '-' }}</p>
                        </div>
                        <div class="bg-base-200/50 rounded-xl p-4">
                            <p class="text-[10px] font-bold uppercase tracking-widest text-base-content/40 mb-1">Date</p>
                            <p class="text-[14px] font-semibold text-base-content">
                                {{ $selectedPayment->paid_at ? \Carbon\Carbon::parse($selectedPayment->paid_at)->format('M j, Y g:i A') : $selectedPayment->created_at->format('M j, Y g:i A') }}
                            </p>
                        </div>
                        <div class="bg-base-200/50 rounded-xl p-4">
                            <p class="text-[10px] font-bold uppercase tracking-widest text-base-content/40 mb-1">Currency</p>
                            <p class="text-[14px] font-semibold text-base-content">{{ $selectedPayment->currency ?? 'GHS' }}</p>
                        </div>
                    </div>

                    @if($selectedPayment->gateway_reference)
                        <div class="bg-base-200/50 rounded-xl p-4">
                            <p class="text-[10px] font-bold uppercase tracking-widest text-base-content/40 mb-1">Transaction Reference</p>
                            <p class="text-[13px] font-mono font-semibold text-base-content break-all">{{ $selectedPayment->gateway_reference }}</p>
                        </div>
                    @endif

                    {{-- Booking Info --}}
                    <div class="border-t border-base-content/10 pt-4">
                        <p class="text-[10px] font-bold uppercase tracking-widest text-base-content/40 mb-3">Booking</p>
                        <a href="{{ route('dashboard.bookings.show', $selectedPayment->booking->reference) }}" wire:navigate class="flex items-center gap-3 p-3 rounded-xl bg-base-200/50 hover:bg-base-200 transition-colors group">
                            <div class="w-10 h-10 rounded-xl bg-primary/10 flex items-center justify-center">
                                @include('layouts.partials.icons.clipboard-document-list', ['class' => 'w-5 h-5 text-primary'])
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-[13px] font-bold text-base-content group-hover:text-primary transition-colors">{{ $selectedPayment->booking->reference }}</p>
                                <p class="text-[11px] text-base-content/50">
                                    @if($selectedPayment->booking->items->isNotEmpty())
                                        {{ $selectedPayment->booking->items->first()->package->name }}
                                        @if($selectedPayment->booking->items->count() > 1)
                                            +{{ $selectedPayment->booking->items->count() - 1 }} more
                                        @endif
                                    @endif
                                </p>
                            </div>
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-base-content/30 group-hover:text-primary transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                            </svg>
                        </a>
                    </div>
                </div>

                {{-- Modal Footer --}}
                <div class="flex items-center justify-between p-6 border-t border-base-content/10 bg-base-200/30">
                    <button wire:click="closeModal" class="btn btn-ghost btn-sm">Close</button>
                    @if($selectedPayment->status === \App\Enums\PaymentGatewayStatus::Successful)
                        <a href="{!! app(\App\Services\InvoiceService::class)->getDownloadUrl($selectedPayment->booking) !!}" target="_blank" class="btn btn-primary btn-sm gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            Download Invoice
                        </a>
                    @endif
                </div>
            </div>
        </div>
    @endif
</div>
