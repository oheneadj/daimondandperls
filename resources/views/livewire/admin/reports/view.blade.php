<div class="space-y-6 pb-10" x-data="{ showCustomDates: @entangle('period').live === 'custom' }">
    {{-- Page Header --}}
    <div class="space-y-4">
        <div>
            <h1 class="text-[24px] md:text-[28px] font-semibold text-base-content leading-tight">
                {{ __('Reports & Analytics') }}
            </h1>
            <p class="text-[13px] md:text-[14px] text-base-content/50 mt-1">{{ __('Deep insights into your catering operations and revenue streams.') }}</p>
        </div>

        @php
            $periodStyles = [
                'today' => ['active' => 'bg-[#9ABC05]/10 text-[#6B8A00] border border-[#9ABC05]/30 shadow-sm', 'dot' => 'bg-[#9ABC05]'],
                'this_week' => ['active' => 'bg-[#F96015]/10 text-[#D94E0E] border border-[#F96015]/30 shadow-sm', 'dot' => 'bg-[#F96015]'],
                'this_month' => ['active' => 'bg-[#A31C4E]/10 text-[#A31C4E] border border-[#A31C4E]/30 shadow-sm', 'dot' => 'bg-[#A31C4E]'],
                'custom' => ['active' => 'bg-neutral/10 text-neutral border border-neutral/20 shadow-sm', 'dot' => 'bg-neutral'],
            ];
        @endphp

        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            <div class="flex flex-wrap items-center gap-2">
                @foreach(['today' => 'Today', 'this_week' => 'Week', 'this_month' => 'Month'] as $key => $label)
                    <button wire:key="period-{{ $key }}" wire:click="setPeriod('{{ $key }}')" wire:loading.attr="disabled"
                        class="inline-flex items-center gap-2 px-3 sm:px-4 py-2 text-[11px] font-bold uppercase tracking-[0.15em] rounded-lg transition-all duration-200 {{ $period === $key ? $periodStyles[$key]['active'] : 'bg-base-200 text-base-content/50 hover:bg-base-200-mid hover:text-base-content border border-transparent' }}">
                        @if($period === $key)
                            <span class="w-2 h-2 rounded-full {{ $periodStyles[$key]['dot'] }} animate-pulse"></span>
                        @endif
                        <span wire:loading.remove wire:target="setPeriod('{{ $key }}')">{{ __($label) }}</span>
                        <span wire:loading wire:target="setPeriod('{{ $key }}')" class="flex items-center gap-1">
                            <svg class="w-3 h-3 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/></svg>
                        </span>
                    </button>
                @endforeach
                <button @click="showCustomDates = !showCustomDates; if(showCustomDates) $wire.set('period', 'custom')"
                    class="inline-flex items-center gap-2 px-3 sm:px-4 py-2 text-[11px] font-bold uppercase tracking-[0.15em] rounded-lg transition-all duration-200 {{ $period === 'custom' ? $periodStyles['custom']['active'] : 'bg-base-200 text-base-content/50 hover:bg-base-200-mid hover:text-base-content border border-transparent' }}">
                    @if($period === 'custom')
                        <span class="w-2 h-2 rounded-full {{ $periodStyles['custom']['dot'] }} animate-pulse"></span>
                    @endif
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    {{ __('Custom') }}
                </button>
            </div>

            <x-ui.button variant="success" size="sm" wire:click="exportCsv" wire:loading.attr="disabled" class="shadow-sm w-full sm:w-auto" title="{{ __('Export CSV') }}">
                <x-slot:icon>
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                </x-slot:icon>
                {{ __('Export CSV') }}
            </x-ui.button>
        </div>
    </div>

    {{-- Custom Date Picker --}}
    <div x-show="showCustomDates" x-collapse x-cloak>
        <x-ui.card class="bg-base-200-mid/30 border-base-content/10">
            <div class="flex flex-wrap items-end gap-6 p-2 sm:w-full">
                <div>
                    <label class="text-[11px] font-bold uppercase tracking-[0.2em] text-base-content/60 mb-2 block">{{ __('From') }}</label>
                    <x-ui.input type="date" wire:model.live="startDate" class="bg-base-100 sm:w-full" />
                </div>
                <div>
                    <label class="text-[11px] font-bold uppercase tracking-[0.2em] text-base-content/60 mb-2 block">{{ __('To') }}</label>
                    <x-ui.input type="date" wire:model.live="endDate" class="bg-base-100 sm:w-full" />
                </div>
            </div>
        </x-ui.card>
    </div>

    {{-- KPI Stats Row --}}
    <div class="grid grid-cols-1 lg:grid-cols-4 gap-3 sm:gap-4">
        <div class="bg-white border border-base-content/5 rounded-lg p-3 sm:p-4 flex items-center gap-3 sm:gap-4">
            <div class="w-9 h-9 sm:w-10 sm:h-10 rounded-lg bg-[#9ABC05]/10 flex items-center justify-center flex-shrink-0">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 sm:w-5 sm:h-5 text-[#9ABC05]" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <div class="min-w-0">
                <p class="text-[16px] sm:text-[20px] font-bold text-base-content truncate">GH₵{{ number_format($stats['total_revenue'], 2) }}</p>
                <p class="text-[9px] sm:text-[10px] font-bold uppercase tracking-widest text-base-content/40">{{ __('Revenue') }}</p>
            </div>
        </div>
        <div class="bg-white border border-base-content/5 rounded-lg p-3 sm:p-4 flex items-center gap-3 sm:gap-4">
            <div class="w-9 h-9 sm:w-10 sm:h-10 rounded-lg bg-[#F96015]/10 flex items-center justify-center flex-shrink-0">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 sm:w-5 sm:h-5 text-[#F96015]" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
            </div>
            <div class="min-w-0">
                <p class="text-[16px] sm:text-[20px] font-bold text-base-content">{{ number_format($stats['total_bookings']) }}</p>
                <p class="text-[9px] sm:text-[10px] font-bold uppercase tracking-widest text-base-content/40">{{ __('Bookings') }}</p>
            </div>
        </div>
        <div class="bg-white border border-base-content/5 rounded-lg p-3 sm:p-4 flex items-center gap-3 sm:gap-4">
            <div class="w-9 h-9 sm:w-10 sm:h-10 rounded-lg bg-[#A31C4E]/10 flex items-center justify-center flex-shrink-0">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 sm:w-5 sm:h-5 text-[#A31C4E]" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/></svg>
            </div>
            <div class="min-w-0">
                <p class="text-[16px] sm:text-[20px] font-bold text-base-content truncate">GH₵{{ number_format($stats['avg_value'], 2) }}</p>
                <p class="text-[9px] sm:text-[10px] font-bold uppercase tracking-widest text-base-content/40">{{ __('Avg Value') }}</p>
            </div>
        </div>
        <div class="bg-white border border-base-content/5 rounded-lg p-3 sm:p-4 flex items-center gap-3 sm:gap-4">
            <div class="w-9 h-9 sm:w-10 sm:h-10 rounded-lg bg-[#FFC926]/10 flex items-center justify-center flex-shrink-0">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 sm:w-5 sm:h-5 text-[#FFC926]" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/></svg>
            </div>
            <div class="min-w-0">
                <p class="text-[16px] sm:text-[20px] font-bold text-base-content truncate" title="{{ $stats['popular_package'] }}">{{ $stats['popular_package'] }}</p>
                <p class="text-[9px] sm:text-[10px] font-bold uppercase tracking-widest text-base-content/40">{{ __('Top Package') }}</p>
            </div>
        </div>
    </div>

    {{-- 2x2 Charts Grid --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        {{-- Revenue Over Time --}}
        <x-ui.card class="shadow-sm">
            <div class="flex items-center justify-between border-b border-base-content/5 pb-4 mb-6">
                <h3 class="text-[16px] font-bold text-base-content">{{ __('Revenue Trajectory') }}</h3>
                <span class="text-[10px] font-bold text-base-content/40 uppercase tracking-widest">{{ __('GH₵ vs Period') }}</span>
            </div>
            <div class="h-[300px]" 
                 x-data="{ 
                    chart: null,
                    data: @entangle('revenueOverTime'),
                    initChart() {
                        if (this.chart) this.chart.destroy();
                        const options = {
                            series: [{ name: 'Revenue', data: this.data.values }],
                            chart: { type: 'area', height: 300, toolbar: { show: false }, zoom: { enabled: false }, fontFamily: 'Outfit' },
                            colors: ['#9ABC05'],
                            dataLabels: { enabled: false },
                            stroke: { curve: 'smooth', width: 3 },
                            fill: { type: 'gradient', gradient: { shadeIntensity: 1, opacityFrom: 0.45, opacityTo: 0.05, stops: [20, 100] } },
                            xaxis: { categories: this.data.labels, axisBorder: { show: false }, axisTicks: { show: false }, labels: { style: { fontSize: '10px', fontWeight: 600, colors: '#1C1A18' } } },
                            yaxis: { labels: { style: { fontSize: '10px', fontWeight: 600, colors: '#1C1A18' }, formatter: (v) => 'GH₵ ' + v.toLocaleString() } },
                            grid: { borderColor: '#E6E8EA', strokeDashArray: 4 }
                        };
                        this.chart = new ApexCharts($refs.revenueChart, options);
                        this.chart.render();
                    }
                 }" x-init="initChart(); $watch('data', () => initChart())" wire:ignore>
                <div x-ref="revenueChart"></div>
            </div>
        </x-ui.card>

        {{-- Bookings by Status (Donut) --}}
        <x-ui.card class="shadow-sm">
            <div class="flex items-center justify-between border-b border-base-content/5 pb-4 mb-6">
                <h3 class="text-[16px] font-bold text-base-content">{{ __('Booking Status') }}</h3>
                <span class="text-[10px] font-bold text-base-content/40 uppercase tracking-widest">{{ __('Breakdown') }}</span>
            </div>
            <div class="h-[300px]" 
                 x-data="{ 
                    chart: null,
                    data: @entangle('statusBreakdown'),
                    initChart() {
                        if (this.chart) this.chart.destroy();
                        const options = {
                            series: Object.values(this.data),
                            chart: { type: 'donut', height: 300, fontFamily: 'Outfit' },
                            labels: Object.keys(this.data).map(s => s.replace('_', ' ').toUpperCase()),
                            colors: ['#F96015', '#9ABC05', '#FFC926', '#D52518', '#121212'],
                            legend: { position: 'bottom', fontSize: '10px', fontWeight: 700, labels: { colors: '#1C1A18' } },
                            plotOptions: { pie: { donut: { size: '75%', labels: { show: true, total: { show: true, label: 'TOTAL', fontSize: '11px', fontWeight: 700, color: '#1C1A18' } } } } },
                            dataLabels: { enabled: false }
                        };
                        this.chart = new ApexCharts($refs.statusChart, options);
                        this.chart.render();
                    }
                 }" x-init="initChart(); $watch('data', () => initChart())" wire:ignore>
                <div x-ref="statusChart"></div>
            </div>
        </x-ui.card>

        {{-- Bookings by Event Type (Horizontal Bar) --}}
        <x-ui.card class="shadow-sm">
            <div class="flex items-center justify-between border-b border-base-content/5 pb-4 mb-6">
                <h3 class="text-[16px] font-bold text-base-content">{{ __('Event Types') }}</h3>
                <span class="text-[10px] font-bold text-base-content/40 uppercase tracking-widest">{{ __('Demand by Category') }}</span>
            </div>
            <div class="h-[300px]" 
                 x-data="{ 
                    chart: null,
                    data: @entangle('eventTypeBreakdown'),
                    initChart() {
                        if (this.chart) this.chart.destroy();
                        const options = {
                            series: [{ data: Object.values(this.data) }],
                            chart: { type: 'bar', height: 300, toolbar: { show: false }, fontFamily: 'Outfit' },
                            plotOptions: { bar: { horizontal: true, borderRadius: 4, barHeight: '60%' } },
                            colors: ['#FFC926'],
                            xaxis: { categories: Object.keys(this.data).map(s => s.toUpperCase()), labels: { style: { fontSize: '10px', fontWeight: 600, colors: '#1C1A18' } } },
                            yaxis: { labels: { style: { fontSize: '10px', fontWeight: 600, colors: '#1C1A18' } } },
                            dataLabels: { enabled: true, style: { fontSize: '10px', fontWeight: 700 } }
                        };
                        this.chart = new ApexCharts($refs.eventChart, options);
                        this.chart.render();
                    }
                 }" x-init="initChart(); $watch('data', () => initChart())" wire:ignore>
                <div x-ref="eventChart"></div>
            </div>
        </x-ui.card>

        {{-- Revenue by Package (Horizontal Bar) --}}
        <x-ui.card class="shadow-sm">
            <div class="flex items-center justify-between border-b border-base-content/5 pb-4 mb-6">
                <h3 class="text-[16px] font-bold text-base-content">{{ __('Package Performance') }}</h3>
                <span class="text-[10px] font-bold text-base-content/40 uppercase tracking-widest">{{ __('Top Revenue Drivers') }}</span>
            </div>
            <div class="h-[300px]" 
                 x-data="{ 
                    chart: null,
                    data: @entangle('revenueByPackage'),
                    initChart() {
                        if (this.chart) this.chart.destroy();
                        const options = {
                            series: [{ data: Object.values(this.data) }],
                            chart: { type: 'bar', height: 300, toolbar: { show: false }, fontFamily: 'Outfit' },
                            plotOptions: { bar: { horizontal: true, borderRadius: 4, barHeight: '60%' } },
                            colors: ['#F96015'],
                            xaxis: { categories: Object.keys(this.data), labels: { style: { fontSize: '10px', fontWeight: 600, colors: '#1C1A18' } } },
                            yaxis: { labels: { style: { fontSize: '10px', fontWeight: 600, colors: '#1C1A18' } } },
                            dataLabels: { enabled: true, formatter: (v) => 'GH₵ ' + v.toLocaleString(), style: { fontSize: '10px', fontWeight: 700 } }
                        };
                        this.chart = new ApexCharts($refs.packageChart, options);
                        this.chart.render();
                    }
                 }" x-init="initChart(); $watch('data', () => initChart())" wire:ignore>
                <div x-ref="packageChart"></div>
            </div>
        </x-ui.card>
    </div>

    {{-- Tables Row --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 overflow-hidden">
        {{-- Upcoming Events --}}
        <x-ui.table>
            <x-slot name="header">
                <x-ui.table.th class="cursor-pointer hover:text-primary transition-colors" wire:click="sortBy('event_date')">
                    {{ __('Event Date') }}
                    @if($sortField === 'event_date')
                        <span class="ml-1">{{ $sortDirection === 'asc' ? '↑' : '↓' }}</span>
                    @endif
                </x-ui.table.th>
                <x-ui.table.th class="cursor-pointer hover:text-primary transition-colors" wire:click="sortBy('customer_id')">
                    {{ __('Client') }}
                    @if($sortField === 'customer_id')
                        <span class="ml-1">{{ $sortDirection === 'asc' ? '↑' : '↓' }}</span>
                    @endif
                </x-ui.table.th>
                <x-ui.table.th align="right" class="cursor-pointer hover:text-primary transition-colors" wire:click="sortBy('total_amount')">
                    {{ __('Amount') }}
                    @if($sortField === 'total_amount')
                        <span class="ml-1">{{ $sortDirection === 'asc' ? '↑' : '↓' }}</span>
                    @endif
                </x-ui.table.th>
            </x-slot>

            @forelse($upcomingEvents as $event)
                <x-ui.table.row wire:key="event-{{ $event->id }}" class="group">
                    <x-ui.table.td>
                        <div class="font-semibold text-[13px] text-base-content">{{ $event->event_date?->format('d M, Y') ?? 'N/A' }}</div>
                        <div class="text-[10px] text-base-content/60">{{ $event->event_start_time }}</div>
                    </x-ui.table.td>
                    <x-ui.table.td>
                        <div class="text-[13px] font-medium text-base-content">{{ $event->customer?->name ?? 'N/A' }}</div>
                        <div class="text-[10px] text-base-content/60">{{ $event->items->first()?->package->name ?? 'Mixed' }}</div>
                    </x-ui.table.td>
                    <x-ui.table.td align="right">
                        <span class="text-[13px] font-bold text-base-content">GH₵ {{ number_format($event->total_amount, 2) }}</span>
                    </x-ui.table.td>
                </x-ui.table.row>
            @empty
                <x-ui.table.empty colspan="3" title="{{ __('No upcoming events') }}" description="{{ __('No events in the next 30 days.') }}" />
            @endforelse

            <x-slot name="pagination">
                <div class="text-[12px] text-base-content/40 font-medium">
                    {{ __('Upcoming Events') }} · {{ __('Next 30 Days') }}
                </div>
            </x-slot>
        </x-ui.table>

        {{-- Payment Summary --}}
        <x-ui.card padding="none" class="shadow-sm overflow-hidden flex flex-col">
            <div class="px-6 py-4 border-b border-base-content/5 flex items-center justify-between">
                <h3 class="text-[16px] font-bold text-base-content">{{ __('Payment Summary') }}</h3>
                <span class="text-[10px] font-bold text-base-content/40 uppercase tracking-widest">{{ __('Financial Snapshot') }}</span>
            </div>
            <div class="p-6 space-y-5 flex-1">
                @foreach([
                    ['label' => 'Total Paid & Verified', 'value' => $paymentSummary['paid'], 'color' => 'bg-secondary', 'text' => 'text-secondary', 'id' => 'paid'],
                    ['label' => 'Pending Settlement', 'value' => $paymentSummary['pending'], 'color' => 'bg-dp-warning', 'text' => 'text-dp-warning', 'id' => 'pending'],
                    ['label' => 'Manual Verification', 'value' => $paymentSummary['needs_verification'], 'color' => 'bg-primary-border', 'text' => 'text-primary', 'id' => 'needs_verification'],
                    ['label' => 'Failed Transactions', 'value' => $paymentSummary['failed'], 'color' => 'bg-dp-danger', 'text' => 'text-error', 'id' => 'failed'],
                ] as $item)
                    <div wire:key="summary-{{ $item['id'] }}" class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="w-1.5 h-10 rounded-full {{ $item['color'] }}"></div>
                            <span class="text-[13px] font-semibold text-base-content">{{ __($item['label']) }}</span>
                        </div>
                        <div class="text-right">
                            <div class="text-[16px] font-bold {{ $item['text'] }}">GH₵ {{ number_format($item['value'], 2) }}</div>
                            <div class="text-[10px] font-bold text-base-content/40 uppercase tracking-widest mt-0.5">{{ __('Amount') }}</div>
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="mt-auto p-4 bg-base-200-mid/20 border-t border-base-content/5 text-center">
                <p class="text-[11px] text-base-content/40">
                    {{ __('Reconcile manual verifications with bank statements before final confirmation.') }}
                </p>
            </div>
        </x-ui.card>
    </div>

    @push('scripts')
        {{-- ApexCharts is now bundled in app.js --}}
    @endpush
</div>
