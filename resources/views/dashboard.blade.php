<div class="space-y-6 pb-10">

    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-[24px] md:text-[28px] font-semibold text-base-content leading-tight">
                {{ __('Good') }} {{ now()->hour < 12 ? __('morning') : (now()->hour < 17 ? __('afternoon') : __('evening')) }},
                <span class="text-primary">{{ auth()->user()->displayName() }}</span>
            </h1>
            <p class="text-[13px] text-base-content/50 mt-1">{{ now()->format('l, d F Y') }}</p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.reports.index') }}" wire:navigate
               class="inline-flex items-center gap-2 px-4 py-2 text-[11px] font-bold uppercase tracking-widest bg-base-200 text-base-content/60 hover:bg-base-300 hover:text-base-content rounded-lg transition-all">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                {{ __('Full Report') }}
            </a>
            <button wire:click="loadData" wire:loading.attr="disabled"
                class="inline-flex items-center justify-center w-9 h-9 rounded-lg bg-white border border-base-content/10 text-base-content/40 hover:text-primary hover:border-primary/30 transition-all shadow-sm">
                <svg wire:loading.class="animate-spin" wire:target="loadData" xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
            </button>
        </div>
    </div>

    {{-- KPI Row --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
        {{-- Today's Bookings --}}
        <a href="{{ route('admin.bookings.index') }}" wire:navigate
           class="bg-white border border-base-content/5 rounded-lg p-4 flex items-center gap-4 hover:shadow-md hover:border-[#F96015]/20 transition-all group">
            <div class="w-10 h-10 rounded-lg bg-[#F96015]/10 flex items-center justify-center shrink-0 group-hover:bg-[#F96015]/20 transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-[#F96015]" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
            </div>
            <div class="min-w-0">
                <p class="text-[20px] font-bold text-base-content leading-none">{{ $totalBookingsToday }}</p>
                <p class="text-[10px] font-bold uppercase tracking-widest text-base-content/40 mt-1">{{ __("Today's Bookings") }}</p>
            </div>
        </a>

        {{-- Monthly Revenue --}}
        <div class="bg-white border border-base-content/5 rounded-lg p-4 flex items-center gap-4">
            <div class="w-10 h-10 rounded-lg bg-[#9ABC05]/10 flex items-center justify-center shrink-0">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-[#9ABC05]" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <div class="min-w-0">
                <p class="text-[20px] font-bold text-base-content leading-none truncate">GH₵{{ number_format($revenueMonth, 0) }}</p>
                <p class="text-[10px] font-bold uppercase tracking-widest text-base-content/40 mt-1">{{ __('This Month') }}</p>
            </div>
        </div>

        {{-- Needs Attention --}}
        <a href="{{ route('admin.bookings.index', ['paymentStatus' => 'unpaid']) }}" wire:navigate
           class="bg-white border border-base-content/5 rounded-lg p-4 flex items-center gap-4 hover:shadow-md hover:border-[#D52518]/20 transition-all group {{ $needsAttentionCount > 0 ? 'border-[#D52518]/20' : '' }}">
            <div class="w-10 h-10 rounded-lg {{ $needsAttentionCount > 0 ? 'bg-[#D52518]/10' : 'bg-base-200' }} flex items-center justify-center shrink-0 transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 {{ $needsAttentionCount > 0 ? 'text-[#D52518]' : 'text-base-content/30' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
            </div>
            <div class="min-w-0">
                <p class="text-[20px] font-bold {{ $needsAttentionCount > 0 ? 'text-[#D52518]' : 'text-base-content' }} leading-none">{{ $needsAttentionCount }}</p>
                <p class="text-[10px] font-bold uppercase tracking-widest text-base-content/40 mt-1">{{ __('Need Attention') }}</p>
            </div>
        </a>

        {{-- Next Week Deliveries --}}
        <a href="{{ route('admin.reports.index') }}" wire:navigate
           class="bg-white border border-base-content/5 rounded-lg p-4 flex items-center gap-4 hover:shadow-md hover:border-[#A31C4E]/20 transition-all group">
            <div class="w-10 h-10 rounded-lg bg-[#A31C4E]/10 flex items-center justify-center shrink-0 group-hover:bg-[#A31C4E]/20 transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-[#A31C4E]" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"/></svg>
            </div>
            <div class="min-w-0">
                <p class="text-[20px] font-bold text-base-content leading-none">{{ $nextWeekDeliveriesCount }}</p>
                <p class="text-[10px] font-bold uppercase tracking-widest text-base-content/40 mt-1">{{ __('Next Week Deliveries') }}</p>
            </div>
        </a>
    </div>

    {{-- Revenue Chart + Side Metrics --}}
    <div class="grid grid-cols-1 lg:grid-cols-4 gap-4">

        {{-- Area Chart --}}
        <div class="lg:col-span-3 bg-white border border-base-content/5 rounded-lg shadow-sm overflow-hidden">
            <div class="px-6 py-5 border-b border-base-content/5 flex flex-wrap items-start justify-between gap-4">
                <div>
                    <p class="text-[10px] font-bold uppercase tracking-widest text-base-content/40 mb-1">{{ __('30-Day Revenue') }}</p>
                    <h2 class="text-[22px] font-bold text-base-content">GH₵{{ number_format($totalRevenue, 0) }}
                        <span class="text-[13px] font-medium text-base-content/40 ml-1">{{ __('all time') }}</span>
                    </h2>
                </div>
                <div class="flex items-center gap-6">
                    <div class="text-right">
                        <p class="text-[10px] font-bold uppercase tracking-widest text-base-content/40">{{ __('This Month') }}</p>
                        <p class="text-[16px] font-bold text-[#9ABC05]">GH₵{{ number_format($revenueMonth, 0) }}</p>
                    </div>
                    <div class="w-px h-8 bg-base-content/10"></div>
                    <div class="text-right">
                        <p class="text-[10px] font-bold uppercase tracking-widest text-base-content/40">{{ __('Pipeline') }}</p>
                        <p class="text-[16px] font-bold text-[#FFC926]">GH₵{{ number_format($projectedRevenue, 0) }}</p>
                    </div>
                </div>
            </div>
            <div class="h-[260px] p-4"
                 x-data="{
                    chart: null,
                    data: @entangle('revenueTrends'),
                    initChart() {
                        if (this.chart) this.chart.destroy();
                        this.chart = new ApexCharts(this.$refs.chart, {
                            series: [{ name: 'Revenue', data: this.data.values }],
                            chart: { type: 'area', height: 240, toolbar: { show: false }, zoom: { enabled: false }, fontFamily: 'Outfit', sparkline: { enabled: false } },
                            colors: ['#F96015'],
                            dataLabels: { enabled: false },
                            stroke: { curve: 'smooth', width: 2.5 },
                            fill: { type: 'gradient', gradient: { shadeIntensity: 1, opacityFrom: 0.35, opacityTo: 0.02, stops: [0, 100] } },
                            xaxis: { categories: this.data.labels, axisBorder: { show: false }, axisTicks: { show: false }, labels: { style: { fontSize: '9px', fontWeight: 700, colors: Array(this.data.labels.length).fill('#9CA3AF') }, rotate: 0 }, tickAmount: 7 },
                            yaxis: { show: false },
                            grid: { borderColor: '#F3F4F6', strokeDashArray: 3, padding: { left: 0, right: 0 } },
                            tooltip: { theme: 'dark', x: { show: true }, y: { formatter: (v) => 'GH₵ ' + v.toLocaleString() } },
                            markers: { size: 0, hover: { size: 5, sizeOffset: 2 } }
                        });
                        this.chart.render();
                    }
                 }"
                 x-init="initChart(); $watch('data', () => initChart())"
                 wire:ignore>
                <div x-ref="chart"></div>
            </div>
        </div>

        {{-- Side Metrics --}}
        <div class="flex flex-col gap-4">
            {{-- AOV --}}
            <div class="bg-white border border-base-content/5 rounded-lg p-5 flex-1">
                <p class="text-[10px] font-bold uppercase tracking-widest text-base-content/40 mb-3">{{ __('Avg Order Value') }}</p>
                <p class="text-[26px] font-bold text-base-content leading-none">GH₵{{ number_format($averageOrderValue, 0) }}</p>
                <div class="flex items-center gap-1.5 mt-2">
                    @if($aovChange >= 0)
                        <span class="inline-flex items-center gap-1 text-[10px] font-bold text-[#9ABC05] bg-[#9ABC05]/10 px-2 py-0.5 rounded-full">
                            <svg class="w-2.5 h-2.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 10l7-7m0 0l7 7m-7-7v18"/></svg>
                            {{ number_format(abs($aovChange), 1) }}%
                        </span>
                    @else
                        <span class="inline-flex items-center gap-1 text-[10px] font-bold text-[#D52518] bg-[#D52518]/10 px-2 py-0.5 rounded-full">
                            <svg class="w-2.5 h-2.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M19 14l-7 7m0 0l-7-7m7 7V3"/></svg>
                            {{ number_format(abs($aovChange), 1) }}%
                        </span>
                    @endif
                    <span class="text-[10px] text-base-content/40">{{ __('vs last month') }}</span>
                </div>
            </div>

            {{-- New customers this week --}}
            <div class="bg-white border border-base-content/5 rounded-lg p-5 flex-1">
                <p class="text-[10px] font-bold uppercase tracking-widest text-base-content/40 mb-3">{{ __('New Customers') }}</p>
                <p class="text-[26px] font-bold text-base-content leading-none">{{ $newCustomersThisWeek }}</p>
                <div class="flex items-center gap-1.5 mt-2">
                    @if($acquisitionRate >= 0)
                        <span class="inline-flex items-center gap-1 text-[10px] font-bold text-[#9ABC05] bg-[#9ABC05]/10 px-2 py-0.5 rounded-full">
                            <svg class="w-2.5 h-2.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 10l7-7m0 0l7 7m-7-7v18"/></svg>
                            {{ number_format(abs($acquisitionRate), 0) }}%
                        </span>
                    @else
                        <span class="inline-flex items-center gap-1 text-[10px] font-bold text-[#D52518] bg-[#D52518]/10 px-2 py-0.5 rounded-full">
                            <svg class="w-2.5 h-2.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M19 14l-7 7m0 0l-7-7m7 7V3"/></svg>
                            {{ number_format(abs($acquisitionRate), 0) }}%
                        </span>
                    @endif
                    <span class="text-[10px] text-base-content/40">{{ __('vs last week') }}</span>
                </div>
            </div>

            {{-- Month bookings --}}
            <div class="bg-white border border-base-content/5 rounded-lg p-5 flex-1">
                <p class="text-[10px] font-bold uppercase tracking-widest text-base-content/40 mb-3">{{ __('Monthly Bookings') }}</p>
                <p class="text-[26px] font-bold text-base-content leading-none">{{ $totalBookingsMonth }}</p>
                <p class="text-[10px] text-base-content/40 mt-2">{{ now()->format('F Y') }}</p>
            </div>
        </div>
    </div>

    {{-- Operations Row --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">

        {{-- Pipeline Status --}}
        <div class="bg-white border border-base-content/5 rounded-lg shadow-sm p-6 flex flex-col">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <p class="text-[10px] font-bold uppercase tracking-widest text-base-content/40 mb-1">{{ __('This Month') }}</p>
                    <h3 class="text-[16px] font-bold text-base-content">{{ __('Order Pipeline') }}</h3>
                </div>
                <div class="w-9 h-9 rounded-lg bg-[#F96015]/10 flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-[#F96015]" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                </div>
            </div>
            <div class="space-y-4 flex-1">
                @foreach($this->statusBreakdown as $stat)
                    <div wire:key="status-{{ $stat['label'] }}">
                        <div class="flex items-center justify-between mb-1.5">
                            <span class="text-[12px] font-semibold text-base-content">{{ $stat['label'] }}</span>
                            <span class="text-[10px] font-bold px-2 py-0.5 rounded-full {{ $stat['badgeClass'] }}">{{ $stat['count'] }}</span>
                        </div>
                        <div class="h-1.5 w-full bg-base-200 rounded-full overflow-hidden">
                            <div class="h-full rounded-full transition-all duration-700"
                                 style="width: {{ $stat['percentage'] }}%; background-color: {{ $stat['barColor'] }}"></div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        {{-- Booking Type Mix --}}
        <div class="bg-white border border-base-content/5 rounded-lg shadow-sm p-6 flex flex-col">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <p class="text-[10px] font-bold uppercase tracking-widest text-base-content/40 mb-1">{{ __('All Time') }}</p>
                    <h3 class="text-[16px] font-bold text-base-content">{{ __('Booking Mix') }}</h3>
                </div>
                <div class="w-9 h-9 rounded-lg bg-[#FFC926]/10 flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-[#FFC926]" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z"/></svg>
                </div>
            </div>
            @if(empty($bookingTypeMix))
                <div class="flex-1 flex items-center justify-center">
                    <p class="text-[12px] text-base-content/30 italic">{{ __('No bookings yet') }}</p>
                </div>
            @else
                <div class="h-[160px] relative"
                     x-data="{
                        chart: null,
                        initChart() {
                            if (this.chart) this.chart.destroy();
                            this.chart = new ApexCharts(this.$refs.donut, {
                                series: @js(array_values($bookingTypeMix)),
                                labels: @js(array_keys($bookingTypeMix)),
                                chart: { type: 'donut', height: 160, toolbar: { show: false }, fontFamily: 'Outfit' },
                                colors: ['#FFC926', '#F96015', '#9ABC05', '#A31C4E', '#18542A', '#D52518'],
                                dataLabels: { enabled: false },
                                legend: { show: false },
                                plotOptions: { pie: { donut: { size: '78%', labels: { show: true, total: { show: true, label: 'TOTAL', fontSize: '9px', fontWeight: 700, color: '#9CA3AF', formatter: (w) => w.globals.seriesTotals.reduce((a,b) => a+b, 0) } } } } },
                                stroke: { width: 0 },
                                tooltip: { theme: 'dark' }
                            });
                            this.chart.render();
                        }
                     }"
                     x-init="initChart()"
                     wire:ignore>
                    <div x-ref="donut"></div>
                </div>
                <div class="mt-4 space-y-2">
                    @php $mixColors = ['#FFC926', '#F96015', '#9ABC05', '#A31C4E', '#18542A', '#D52518']; $mi = 0; $mixTotal = array_sum($bookingTypeMix); @endphp
                    @foreach($bookingTypeMix as $type => $count)
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-2">
                                <div class="w-2 h-2 rounded-full shrink-0" style="background-color: {{ $mixColors[$mi % 6] }}"></div>
                                <span class="text-[11px] font-semibold text-base-content/60">{{ $type }}</span>
                            </div>
                            <span class="text-[11px] font-bold text-base-content">{{ $mixTotal > 0 ? round(($count / $mixTotal) * 100) : 0 }}%</span>
                        </div>
                        @php $mi++; @endphp
                    @endforeach
                </div>
            @endif
        </div>

        {{-- Top Customers --}}
        <div class="bg-white border border-base-content/5 rounded-lg shadow-sm flex flex-col overflow-hidden">
            <div class="p-6 pb-4 border-b border-base-content/5">
                <p class="text-[10px] font-bold uppercase tracking-widest text-base-content/40 mb-1">{{ __('By Lifetime Value') }}</p>
                <h3 class="text-[16px] font-bold text-base-content">{{ __('Top Customers') }}</h3>
            </div>
            <div class="divide-y divide-base-content/5 flex-1">
                @forelse($topVIPs as $index => $vip)
                    @php
                        $vipColors = ['#FFC926', '#F96015', '#9ABC05', '#A31C4E', '#18542A'];
                        $vipBg = ['bg-[#FFC926]/10', 'bg-[#F96015]/10', 'bg-[#9ABC05]/10', 'bg-[#A31C4E]/10', 'bg-[#18542A]/10'];
                    @endphp
                    <div wire:key="vip-{{ $index }}"
                         class="px-6 py-3.5 flex items-center justify-between hover:bg-base-200/40 transition-colors">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-full {{ $vipBg[$index % 5] }} flex items-center justify-center text-[11px] font-black shrink-0"
                                 style="color: {{ $vipColors[$index % 5] }}">
                                {{ strtoupper(substr($vip['name'], 0, 2)) }}
                            </div>
                            <div>
                                <p class="text-[13px] font-semibold text-base-content leading-tight">{{ $vip['name'] }}</p>
                                <p class="text-[10px] text-base-content/40">LTV: GH₵{{ number_format($vip['payments_sum_amount'], 0) }}</p>
                            </div>
                        </div>
                        <span class="text-[10px] font-black text-base-content/20">#{{ $index + 1 }}</span>
                    </div>
                @empty
                    <div class="p-8 text-center">
                        <p class="text-[12px] text-base-content/30 italic">{{ __('No customer data yet') }}</p>
                    </div>
                @endforelse
            </div>
            <div class="px-6 py-3 border-t border-base-content/5">
                <a href="{{ route('admin.customers.index') }}" wire:navigate
                   class="text-[11px] font-bold text-base-content/40 hover:text-primary transition-colors">
                    {{ __('View all customers') }} →
                </a>
            </div>
        </div>
    </div>

    {{-- Recent Bookings --}}
    <div class="bg-white border border-base-content/5 rounded-lg shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-base-content/5 flex items-center justify-between">
            <div>
                <h3 class="text-[16px] font-bold text-base-content">{{ __('Recent Bookings') }}</h3>
                <p class="text-[11px] text-base-content/40 mt-0.5">{{ __('Latest 8 across all booking types') }}</p>
            </div>
            <a href="{{ route('admin.bookings.index') }}" wire:navigate
               class="text-[11px] font-bold text-base-content/40 hover:text-primary transition-colors">
                {{ __('View all') }} →
            </a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="border-b border-base-content/5">
                        <th class="px-6 py-3 text-left text-[10px] font-bold uppercase tracking-widest text-base-content/40">{{ __('Reference') }}</th>
                        <th class="px-6 py-3 text-left text-[10px] font-bold uppercase tracking-widest text-base-content/40">{{ __('Customer') }}</th>
                        <th class="px-6 py-3 text-left text-[10px] font-bold uppercase tracking-widest text-base-content/40">{{ __('Type') }}</th>
                        <th class="px-6 py-3 text-left text-[10px] font-bold uppercase tracking-widest text-base-content/40">{{ __('Amount') }}</th>
                        <th class="px-6 py-3 text-left text-[10px] font-bold uppercase tracking-widest text-base-content/40">{{ __('Status') }}</th>
                        <th class="px-6 py-3 text-left text-[10px] font-bold uppercase tracking-widest text-base-content/40">{{ __('Payment') }}</th>
                        <th class="px-6 py-3 text-right text-[10px] font-bold uppercase tracking-widest text-base-content/40">{{ __('Date') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-base-content/5">
                    @forelse($recentActivity as $booking)
                        @php
                            $typeLabel = match($booking['booking_type'] ?? null) {
                                'meal' => 'Meal Order',
                                'event' => ucfirst($booking['event_type'] ?? 'Event'),
                                default => 'Booking',
                            };
                            $statusColors = [
                                'pending' => 'bg-[#FFC926]/10 text-[#b8910a]',
                                'confirmed' => 'bg-[#F96015]/10 text-[#F96015]',
                                'in_preparation' => 'bg-[#A31C4E]/10 text-[#A31C4E]',
                                'ready_for_delivery' => 'bg-[#9ABC05]/10 text-[#6d8504]',
                                'completed' => 'bg-[#18542A]/10 text-[#18542A]',
                                'cancelled' => 'bg-[#D52518]/10 text-[#D52518]',
                            ];
                            $paymentColors = [
                                'paid' => 'bg-[#9ABC05]/10 text-[#6d8504]',
                                'pending' => 'bg-[#FFC926]/10 text-[#b8910a]',
                                'unpaid' => 'bg-[#D52518]/10 text-[#D52518]',
                                'failed' => 'bg-[#D52518]/10 text-[#D52518]',
                            ];
                        @endphp
                        <tr wire:key="booking-{{ $booking['id'] }}" class="hover:bg-base-200/30 transition-colors">
                            <td class="px-6 py-3.5">
                                <a href="{{ route('admin.bookings.show', $booking['reference']) }}" wire:navigate
                                   class="inline-flex items-center px-2.5 py-1 rounded-md bg-primary/10 text-primary text-[11px] font-bold hover:bg-primary hover:text-white transition-all font-mono">
                                    {{ $booking['reference'] }}
                                </a>
                            </td>
                            <td class="px-6 py-3.5">
                                <div class="flex items-center gap-2.5">
                                    <div class="w-7 h-7 rounded-full bg-base-200 flex items-center justify-center text-[9px] font-bold text-base-content/50 shrink-0">
                                        {{ strtoupper(substr($booking['customer']['name'] ?? 'GU', 0, 2)) }}
                                    </div>
                                    <span class="text-[13px] font-medium text-base-content">{{ $booking['customer']['name'] ?? 'Guest' }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-3.5">
                                <span class="text-[11px] font-semibold text-base-content/60">{{ $typeLabel }}</span>
                            </td>
                            <td class="px-6 py-3.5">
                                <span class="text-[13px] font-bold text-base-content">GH₵{{ number_format($booking['total_amount'], 0) }}</span>
                            </td>
                            <td class="px-6 py-3.5">
                                <span class="inline-flex px-2.5 py-0.5 rounded-full text-[10px] font-bold {{ $statusColors[$booking['status'] ?? ''] ?? 'bg-base-200 text-base-content/50' }}">
                                    {{ ucfirst(str_replace('_', ' ', $booking['status'] ?? '—')) }}
                                </span>
                            </td>
                            <td class="px-6 py-3.5">
                                <span class="inline-flex px-2.5 py-0.5 rounded-full text-[10px] font-bold {{ $paymentColors[$booking['payment_status'] ?? ''] ?? 'bg-base-200 text-base-content/50' }}">
                                    {{ ucfirst($booking['payment_status'] ?? '—') }}
                                </span>
                            </td>
                            <td class="px-6 py-3.5 text-right">
                                <span class="text-[11px] text-base-content/40">{{ \Carbon\Carbon::parse($booking['created_at'])->format('d M, H:i') }}</span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-16 text-center">
                                <p class="text-[13px] text-base-content/30 font-medium">{{ __('No bookings yet') }}</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
