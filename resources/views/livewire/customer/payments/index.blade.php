<div>
    <!-- Header -->
    <div class="mb-8 flex flex-col md:flex-row md:items-end justify-between gap-6">
        <div>
            <h1 class="text-3xl font-semibold text-base-content mb-2">Payment History</h1>
            <p class="text-base-content/60 text-[15px] font-medium">View all your transactions and download invoices.</p>
        </div>
    </div>

    <!-- Filter -->
    <div class="bg-white border border-base-content/10 rounded-2xl p-4 mb-6 shadow-sm">
        <div class="flex gap-3">
            <select wire:model.live="status" class="bg-base-200/50 border border-base-content/10 text-[13px] rounded-xl px-3 py-2.5 focus:ring-2 focus:ring-primary/20 focus:border-primary/30 transition-all outline-none min-w-[180px]">
                <option value="">All Statuses</option>
                @foreach(\App\Enums\PaymentGatewayStatus::cases() as $s)
                    <option value="{{ $s->value }}">{{ $s->name }}</option>
                @endforeach
            </select>
        </div>
    </div>

    <!-- Payments List -->
    @if($payments->isEmpty())
        <div class="bg-white border border-base-content/10 rounded-2xl p-12 text-center shadow-sm">
            <div class="size-20 bg-base-200 rounded-full flex items-center justify-center mx-auto mb-6 text-primary/20">
                <svg xmlns="http://www.w3.org/2000/svg" class="size-10" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                </svg>
            </div>
            <h3 class="text-xl font-semibold text-base-content mb-3">No payments found</h3>
            <p class="text-base-content/60 text-[14px] max-w-md mx-auto font-medium">
                @if($status)
                    No payments match this filter.
                @else
                    You don't have any payment records yet.
                @endif
            </p>
        </div>
    @else
        <div class="bg-white border border-base-content/10 rounded-2xl shadow-sm overflow-hidden">
            <!-- Table Header (desktop) -->
            <div class="hidden md:grid grid-cols-12 gap-4 px-6 py-3 bg-base-200/30 border-b border-base-content/10 text-[10px] font-bold text-base-content/50 uppercase tracking-widest">
                <div class="col-span-3">Booking</div>
                <div class="col-span-2">Amount</div>
                <div class="col-span-2">Method</div>
                <div class="col-span-2">Status</div>
                <div class="col-span-2">Date</div>
                <div class="col-span-1"></div>
            </div>

            <div class="divide-y divide-base-content/10">
                @foreach($payments as $payment)
                    <div wire:key="payment-{{ $payment->id }}" class="px-6 py-4">
                        <!-- Desktop -->
                        <div class="hidden md:grid grid-cols-12 gap-4 items-center">
                            <div class="col-span-3">
                                <a href="{{ route('dashboard.bookings.show', $payment->booking->reference) }}" wire:navigate class="font-mono text-[13px] font-bold text-primary hover:underline">
                                    {{ $payment->booking->reference }}
                                </a>
                            </div>
                            <div class="col-span-2">
                                <span class="text-[14px] font-bold text-base-content">GH&#8373; {{ number_format($payment->amount, 2) }}</span>
                            </div>
                            <div class="col-span-2">
                                <span class="text-[13px] font-medium text-base-content/70">{{ $payment->method?->name ?? '-' }}</span>
                            </div>
                            <div class="col-span-2">
                                @php
                                    $statusStyle = match($payment->status) {
                                        \App\Enums\PaymentGatewayStatus::Successful => 'bg-success/10 text-success',
                                        \App\Enums\PaymentGatewayStatus::Failed => 'bg-error/10 text-error',
                                        \App\Enums\PaymentGatewayStatus::Pending => 'bg-dp-warning/10 text-dp-warning',
                                        \App\Enums\PaymentGatewayStatus::Refunded => 'bg-dp-info/10 text-dp-info',
                                        default => 'bg-base-200 text-base-content/60',
                                    };
                                @endphp
                                <span class="px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wide {{ $statusStyle }}">
                                    {{ $payment->status->name }}
                                </span>
                            </div>
                            <div class="col-span-2">
                                <span class="text-[13px] text-base-content/60 font-medium">
                                    {{ $payment->paid_at ? \Carbon\Carbon::parse($payment->paid_at)->format('M j, Y') : $payment->created_at->format('M j, Y') }}
                                </span>
                            </div>
                            <div class="col-span-1 text-right">
                                @if($payment->status === \App\Enums\PaymentGatewayStatus::Successful)
                                    <a href="{!! app(\App\Services\InvoiceService::class)->getDownloadUrl($payment->booking) !!}" target="_blank" class="text-base-content/40 hover:text-primary transition-colors" title="Download Invoice">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                        </svg>
                                    </a>
                                @endif
                            </div>
                        </div>

                        <!-- Mobile -->
                        <div class="md:hidden space-y-3">
                            <div class="flex items-center justify-between">
                                <a href="{{ route('dashboard.bookings.show', $payment->booking->reference) }}" wire:navigate class="font-mono text-[13px] font-bold text-primary hover:underline">
                                    {{ $payment->booking->reference }}
                                </a>
                                @php
                                    $statusStyle = match($payment->status) {
                                        \App\Enums\PaymentGatewayStatus::Successful => 'bg-success/10 text-success',
                                        \App\Enums\PaymentGatewayStatus::Failed => 'bg-error/10 text-error',
                                        \App\Enums\PaymentGatewayStatus::Pending => 'bg-dp-warning/10 text-dp-warning',
                                        \App\Enums\PaymentGatewayStatus::Refunded => 'bg-dp-info/10 text-dp-info',
                                        default => 'bg-base-200 text-base-content/60',
                                    };
                                @endphp
                                <span class="px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wide {{ $statusStyle }}">
                                    {{ $payment->status->name }}
                                </span>
                            </div>
                            <div class="flex items-center justify-between text-[13px]">
                                <span class="font-bold text-base-content">GH&#8373; {{ number_format($payment->amount, 2) }}</span>
                                <span class="text-base-content/50 font-medium">{{ $payment->method?->name ?? '-' }}</span>
                            </div>
                            <div class="text-[12px] text-base-content/50 font-medium">
                                {{ $payment->paid_at ? \Carbon\Carbon::parse($payment->paid_at)->format('M j, Y') : $payment->created_at->format('M j, Y') }}
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Pagination -->
        <div class="mt-8">
            {{ $payments->links() }}
        </div>
    @endif
</div>
