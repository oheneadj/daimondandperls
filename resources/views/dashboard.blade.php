<div class="space-y-6 pb-10">

    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-[24px] md:text-[28px] font-semibold text-base-content leading-tight">
                {{ now()->hour < 12 ? 'Good morning' : (now()->hour < 17 ? 'Good afternoon' : 'Good evening') }},
                <span class="text-primary">{{ auth()->user()->displayName() }}</span>
            </h1>
            <p class="text-[13px] text-base-content/50 mt-1">{{ now()->format('l, d F Y') }}</p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.reports.index') }}" wire:navigate
               class="inline-flex items-center gap-2 px-4 py-2 text-[11px] font-bold uppercase tracking-widest bg-base-200 text-base-content/60 hover:bg-base-300 hover:text-base-content rounded-lg transition-all">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                Full Report
            </a>
            <button wire:click="loadData" wire:loading.attr="disabled"
                class="inline-flex items-center justify-center w-9 h-9 rounded-lg bg-white border border-base-content/10 text-base-content/40 hover:text-primary hover:border-primary/30 transition-all shadow-sm">
                <svg wire:loading.class="animate-spin" wire:target="loadData" xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
            </button>
        </div>
    </div>

    {{-- KPI Row: 2 cols on mobile, 4 on lg --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">

        {{-- Needs Attention --}}
        <a href="{{ route('admin.bookings.index', ['paymentStatus' => 'unpaid']) }}" wire:navigate
           class="bg-white border rounded-xl p-4 flex items-center gap-4 transition-all hover:shadow-md group
                  {{ $needsAttentionCount > 0 ? 'border-[#D52518]/25 shadow-sm shadow-[#D52518]/5' : 'border-base-content/5' }}">
            <div class="w-10 h-10 rounded-lg flex items-center justify-center shrink-0 transition-colors
                        {{ $needsAttentionCount > 0 ? 'bg-[#D52518]/10 group-hover:bg-[#D52518]/20' : 'bg-base-200' }}">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 {{ $needsAttentionCount > 0 ? 'text-[#D52518]' : 'text-base-content/30' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
            </div>
            <div class="min-w-0">
                <p class="text-[20px] font-bold leading-none {{ $needsAttentionCount > 0 ? 'text-[#D52518]' : 'text-base-content' }}">{{ $needsAttentionCount }}</p>
                <p class="text-[10px] font-bold uppercase tracking-widest text-base-content/40 mt-1 leading-tight">Need Attention</p>
            </div>
        </a>

        {{-- Today's Deliveries --}}
        <a href="{{ route('admin.bookings.index') }}" wire:navigate
           class="bg-white border border-base-content/5 rounded-xl p-4 flex items-center gap-4 hover:shadow-md hover:border-[#F96015]/20 transition-all group">
            <div class="w-10 h-10 rounded-lg bg-[#F96015]/10 flex items-center justify-center shrink-0 group-hover:bg-[#F96015]/20 transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-[#F96015]" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"/></svg>
            </div>
            <div class="min-w-0">
                <p class="text-[20px] font-bold text-base-content leading-none">{{ $todayDeliveriesCount }}</p>
                <p class="text-[10px] font-bold uppercase tracking-widest text-base-content/40 mt-1 leading-tight">Today's Deliveries</p>
            </div>
        </a>

        {{-- Next Week Deliveries --}}
        <a href="{{ route('admin.reports.index') }}" wire:navigate
           class="bg-white border border-base-content/5 rounded-xl p-4 flex items-center gap-4 hover:shadow-md hover:border-[#A31C4E]/20 transition-all group">
            <div class="w-10 h-10 rounded-lg bg-[#A31C4E]/10 flex items-center justify-center shrink-0 group-hover:bg-[#A31C4E]/20 transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-[#A31C4E]" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
            </div>
            <div class="min-w-0">
                <p class="text-[20px] font-bold text-base-content leading-none">{{ $nextWeekDeliveriesCount }}</p>
                <p class="text-[10px] font-bold uppercase tracking-widest text-base-content/40 mt-1 leading-tight">Next Week</p>
            </div>
        </a>

        {{-- Unread Messages --}}
        @php $hasMessages = $unreadMessagesCount > 0; @endphp
        <a href="{{ route('admin.contact-messages') }}" wire:navigate
           class="bg-white border rounded-xl p-4 flex items-center gap-4 hover:shadow-md transition-all group
                  {{ $hasMessages ? 'border-[#9ABC05]/25 shadow-sm shadow-[#9ABC05]/5' : 'border-base-content/5' }}">
            <div class="w-10 h-10 rounded-lg flex items-center justify-center shrink-0 relative transition-colors
                        {{ $hasMessages ? 'bg-[#9ABC05]/10 group-hover:bg-[#9ABC05]/20' : 'bg-base-200 group-hover:bg-base-300' }}">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 {{ $hasMessages ? 'text-[#9ABC05]' : 'text-base-content/30' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                @if($hasMessages)
                    <span class="absolute -top-1 -right-1 w-3 h-3 bg-[#9ABC05] rounded-full border-2 border-white"></span>
                @endif
            </div>
            <div class="min-w-0">
                <p class="text-[20px] font-bold leading-none {{ $hasMessages ? 'text-[#9ABC05]' : 'text-base-content' }}">{{ $unreadMessagesCount }}</p>
                <p class="text-[10px] font-bold uppercase tracking-widest text-base-content/40 mt-1 leading-tight">Unread Messages</p>
            </div>
        </a>
    </div>

    {{-- Revenue Chart + Side Cards --}}
    {{-- On mobile: stacked. On lg: chart takes 3/4, side cards take 1/4 --}}
    <div class="grid grid-cols-1 lg:grid-cols-4 gap-4">

        {{-- Area Chart --}}
        <div class="lg:col-span-3 bg-white border border-base-content/5 rounded-xl shadow-sm overflow-hidden">
            <div class="px-5 py-4 border-b border-base-content/5 flex flex-wrap items-start justify-between gap-4">
                <div>
                    <p class="text-[10px] font-bold uppercase tracking-widest text-base-content/40 mb-1">Revenue — Last 30 Days</p>
                    <h2 class="text-[22px] font-bold text-base-content leading-none">
                        GH₵{{ number_format($revenueMonth, 0) }}
                        <span class="text-[13px] font-medium text-base-content/40 ml-1">this month</span>
                    </h2>
                </div>
                <div class="flex items-center gap-5">
                    <div class="text-right">
                        <p class="text-[10px] font-bold uppercase tracking-widest text-base-content/40">Pipeline</p>
                        <p class="text-[15px] font-bold text-[#FFC926]">GH₵{{ number_format($projectedRevenue, 0) }}</p>
                    </div>
                    <div class="w-px h-8 bg-base-content/10"></div>
                    <div class="text-right">
                        <p class="text-[10px] font-bold uppercase tracking-widest text-base-content/40">All Time</p>
                        <p class="text-[15px] font-bold text-base-content/60">GH₵{{ number_format($totalRevenue, 0) }}</p>
                    </div>
                </div>
            </div>
            <div class="h-[240px] p-4"
                 x-data="{
                    chart: null,
                    data: @entangle('revenueTrends'),
                    initChart() {
                        if (this.chart) this.chart.destroy();
                        this.chart = new ApexCharts(this.$refs.chart, {
                            series: [{ name: 'Revenue', data: this.data.values }],
                            chart: { type: 'area', height: 220, toolbar: { show: false }, zoom: { enabled: false }, fontFamily: 'Outfit', sparkline: { enabled: false } },
                            colors: ['#F96015'],
                            dataLabels: { enabled: false },
                            stroke: { curve: 'smooth', width: 2.5 },
                            fill: { type: 'gradient', gradient: { shadeIntensity: 1, opacityFrom: 0.35, opacityTo: 0.02, stops: [0, 100] } },
                            xaxis: { categories: this.data.labels, axisBorder: { show: false }, axisTicks: { show: false }, labels: { style: { fontSize: '9px', fontWeight: 700, colors: Array(this.data.labels.length).fill('#9CA3AF') }, rotate: 0 }, tickAmount: 7 },
                            yaxis: { show: false },
                            grid: { borderColor: '#F3F4F6', strokeDashArray: 3, padding: { left: 0, right: 0 } },
                            tooltip: { theme: 'dark', x: { show: true }, y: { formatter: (v) => 'GH₵ ' + v.toLocaleString() } },
                            markers: { size: 0, hover: { size: 5 } }
                        });
                        this.chart.render();
                    }
                 }"
                 x-init="initChart(); $watch('data', () => initChart())"
                 wire:ignore>
                <div x-ref="chart"></div>
            </div>
        </div>

        {{-- Side Cards: stacked vertically, 3 equal --}}
        <div class="flex flex-col gap-4">

            {{-- This Week's Bookings --}}
            <div class="bg-white border border-base-content/5 rounded-xl p-5 flex-1">
                <p class="text-[10px] font-bold uppercase tracking-widest text-base-content/40 mb-3">This Week's Bookings</p>
                <p class="text-[26px] font-bold text-base-content leading-none">{{ $thisWeekBookings }}</p>
                <div class="flex items-center gap-1.5 mt-2">
                    @if($thisWeekBookingsChange >= 0)
                        <span class="inline-flex items-center gap-1 text-[10px] font-bold text-[#9ABC05] bg-[#9ABC05]/10 px-2 py-0.5 rounded-full">
                            <svg class="w-2.5 h-2.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 10l7-7m0 0l7 7m-7-7v18"/></svg>
                            {{ number_format(abs($thisWeekBookingsChange), 1) }}%
                        </span>
                    @else
                        <span class="inline-flex items-center gap-1 text-[10px] font-bold text-[#D52518] bg-[#D52518]/10 px-2 py-0.5 rounded-full">
                            <svg class="w-2.5 h-2.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M19 14l-7 7m0 0l-7-7m7 7V3"/></svg>
                            {{ number_format(abs($thisWeekBookingsChange), 1) }}%
                        </span>
                    @endif
                    <span class="text-[10px] text-base-content/40">vs last week</span>
                </div>
            </div>

            {{-- Cancellation Rate --}}
            <div class="bg-white border border-base-content/5 rounded-xl p-5 flex-1">
                <p class="text-[10px] font-bold uppercase tracking-widest text-base-content/40 mb-3">Cancellation Rate</p>
                <p class="text-[26px] font-bold text-base-content leading-none">{{ $cancellationRate }}%</p>
                <div class="flex items-center gap-1.5 mt-2">
                    {{-- For cancellation rate: going DOWN is good (green), going UP is bad (red) --}}
                    @if($cancellationRateChange <= 0)
                        <span class="inline-flex items-center gap-1 text-[10px] font-bold text-[#9ABC05] bg-[#9ABC05]/10 px-2 py-0.5 rounded-full">
                            <svg class="w-2.5 h-2.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M19 14l-7 7m0 0l-7-7m7 7V3"/></svg>
                            {{ number_format(abs($cancellationRateChange), 1) }}pp
                        </span>
                    @else
                        <span class="inline-flex items-center gap-1 text-[10px] font-bold text-[#D52518] bg-[#D52518]/10 px-2 py-0.5 rounded-full">
                            <svg class="w-2.5 h-2.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 10l7-7m0 0l7 7m-7-7v18"/></svg>
                            +{{ number_format($cancellationRateChange, 1) }}pp
                        </span>
                    @endif
                    <span class="text-[10px] text-base-content/40">vs last month</span>
                </div>
            </div>

            {{-- Pending Event Quotes --}}
            <a href="{{ route('admin.bookings.index') }}" wire:navigate
               class="bg-white border border-base-content/5 rounded-xl p-5 flex-1 hover:shadow-md hover:border-[#FFC926]/30 transition-all group block">
                <p class="text-[10px] font-bold uppercase tracking-widest text-base-content/40 mb-3">Pending Quotes</p>
                <p class="text-[26px] font-bold text-base-content leading-none">{{ $pendingEventQuotes }}</p>
                <p class="text-[10px] text-base-content/40 mt-2">awaiting customer payment</p>
            </a>
        </div>
    </div>

    {{-- Operations Row: 1 col mobile, 3 cols on lg --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">

        {{-- Order Pipeline --}}
        <div class="bg-white border border-base-content/5 rounded-xl shadow-sm p-6 flex flex-col">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <p class="text-[10px] font-bold uppercase tracking-widest text-base-content/40 mb-1">This Month</p>
                    <h3 class="text-[16px] font-bold text-base-content">Order Pipeline</h3>
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

        {{-- Today's Delivery Schedule --}}
        <div class="bg-white border border-base-content/5 rounded-xl shadow-sm flex flex-col overflow-hidden">
            <div class="p-5 border-b border-base-content/5 flex items-center justify-between">
                <div>
                    <p class="text-[10px] font-bold uppercase tracking-widest text-base-content/40 mb-1">{{ now()->format('l, d M') }}</p>
                    <h3 class="text-[16px] font-bold text-base-content">Today's Schedule</h3>
                </div>
                <span class="text-[11px] font-bold px-2.5 py-1 rounded-full
                             {{ $todayDeliveriesCount > 0 ? 'bg-[#F96015]/10 text-[#F96015]' : 'bg-base-200 text-base-content/40' }}">
                    {{ $todayDeliveriesCount }} {{ Str::plural('order', $todayDeliveriesCount) }}
                </span>
            </div>

            @if(empty($todaySchedule))
                <div class="flex-1 flex flex-col items-center justify-center py-10 text-center px-6">
                    <div class="w-10 h-10 bg-base-200 rounded-full flex items-center justify-center mb-3">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-base-content/20" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    </div>
                    <p class="text-[12px] font-semibold text-base-content/40">No deliveries today</p>
                </div>
            @else
                <div class="divide-y divide-base-content/5 flex-1 overflow-y-auto max-h-[320px]">
                    @foreach($todaySchedule as $item)
                        <div wire:key="schedule-{{ $item['id'] }}" class="px-5 py-3.5 flex items-center gap-3 hover:bg-base-200/30 transition-colors">
                            <div class="w-8 h-8 rounded-lg bg-[#F96015]/10 flex items-center justify-center shrink-0">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-[#F96015]" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                            </div>
                            <div class="min-w-0 flex-1">
                                <p class="text-[13px] font-semibold text-base-content truncate">
                                    {{ $item['booking']['customer']['name'] ?? 'Guest' }}
                                </p>
                                <p class="text-[11px] text-base-content/50 truncate">
                                    {{ $item['package_name'] ?? $item['package']['name'] ?? 'Package' }}
                                    @if(($item['quantity'] ?? 1) > 1)
                                        <span class="text-base-content/30">× {{ $item['quantity'] }}</span>
                                    @endif
                                </p>
                            </div>
                            <a href="{{ route('admin.bookings.show', $item['booking']['reference']) }}" wire:navigate
                               class="text-[10px] font-bold text-base-content/30 hover:text-primary font-mono transition-colors shrink-0">
                                #{{ $item['booking']['reference'] }}
                            </a>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        {{-- Recent Bookings Preview (last 3, link to full table below on mobile) --}}
        <div class="bg-white border border-base-content/5 rounded-xl shadow-sm flex flex-col overflow-hidden">
            <div class="p-5 border-b border-base-content/5 flex items-center justify-between">
                <div>
                    <p class="text-[10px] font-bold uppercase tracking-widest text-base-content/40 mb-1">Latest Activity</p>
                    <h3 class="text-[16px] font-bold text-base-content">Recent Bookings</h3>
                </div>
                <a href="{{ route('admin.bookings.index') }}" wire:navigate
                   class="text-[11px] font-bold text-base-content/40 hover:text-primary transition-colors">
                    View all →
                </a>
            </div>
            <div class="divide-y divide-base-content/5 flex-1">
                @forelse(array_slice($recentActivity, 0, 5) as $booking)
                    @php
                        $statusColors = [
                            'pending'          => 'bg-[#FFC926]/10 text-[#b8910a]',
                            'confirmed'        => 'bg-[#F96015]/10 text-[#F96015]',
                            'in_preparation'   => 'bg-[#A31C4E]/10 text-[#A31C4E]',
                            'ready_for_delivery' => 'bg-[#9ABC05]/10 text-[#6d8504]',
                            'completed'        => 'bg-[#18542A]/10 text-[#18542A]',
                            'cancelled'        => 'bg-[#D52518]/10 text-[#D52518]',
                        ];
                        $paymentColors = [
                            'paid'    => 'bg-[#9ABC05]/10 text-[#6d8504]',
                            'pending' => 'bg-[#FFC926]/10 text-[#b8910a]',
                            'unpaid'  => 'bg-[#D52518]/10 text-[#D52518]',
                            'failed'  => 'bg-[#D52518]/10 text-[#D52518]',
                        ];
                    @endphp
                    <a wire:key="preview-{{ $booking['id'] }}"
                       href="{{ route('admin.bookings.show', $booking['reference']) }}" wire:navigate
                       class="px-5 py-3.5 flex items-center gap-3 hover:bg-base-200/30 transition-colors block">
                        <div class="w-8 h-8 rounded-full bg-base-200 flex items-center justify-center text-[10px] font-bold text-base-content/50 shrink-0">
                            {{ strtoupper(substr($booking['customer']['name'] ?? 'G', 0, 2)) }}
                        </div>
                        <div class="min-w-0 flex-1">
                            <p class="text-[13px] font-semibold text-base-content truncate">{{ $booking['customer']['name'] ?? 'Guest' }}</p>
                            <div class="flex items-center gap-1.5 mt-0.5 flex-wrap">
                                <span class="inline-flex px-1.5 py-0.5 rounded text-[9px] font-bold {{ $statusColors[$booking['status'] ?? ''] ?? 'bg-base-200 text-base-content/50' }}">
                                    {{ ucfirst(str_replace('_', ' ', $booking['status'] ?? '')) }}
                                </span>
                                <span class="inline-flex px-1.5 py-0.5 rounded text-[9px] font-bold {{ $paymentColors[$booking['payment_status'] ?? ''] ?? 'bg-base-200 text-base-content/50' }}">
                                    {{ ucfirst($booking['payment_status'] ?? '') }}
                                </span>
                            </div>
                        </div>
                        <div class="text-right shrink-0">
                            <p class="text-[12px] font-bold text-base-content">GH₵{{ number_format($booking['total_amount'], 0) }}</p>
                            <p class="text-[10px] text-base-content/40">{{ \Carbon\Carbon::parse($booking['created_at'])->format('d M') }}</p>
                        </div>
                    </a>
                @empty
                    <div class="flex-1 flex items-center justify-center py-10">
                        <p class="text-[12px] text-base-content/30 italic">No bookings yet</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    {{-- Full Recent Bookings Table --}}
    {{-- Desktop: full table. Mobile: stacked cards. --}}
    <div class="bg-white border border-base-content/5 rounded-xl shadow-sm overflow-hidden">
        <div class="px-5 py-4 border-b border-base-content/5 flex items-center justify-between">
            <div>
                <h3 class="text-[16px] font-bold text-base-content">All Recent Bookings</h3>
                <p class="text-[11px] text-base-content/40 mt-0.5">Latest 8 across all booking types</p>
            </div>
            <a href="{{ route('admin.bookings.index') }}" wire:navigate
               class="text-[11px] font-bold text-base-content/40 hover:text-primary transition-colors">
                View all →
            </a>
        </div>

        {{-- Mobile: card list (hidden on lg) --}}
        <div class="lg:hidden divide-y divide-base-content/5">
            @forelse($recentActivity as $booking)
                @php
                    $typeLabel = match($booking['booking_type'] ?? null) {
                        'meal'  => 'Meal Order',
                        'event' => ucfirst($booking['event_type'] ?? 'Event'),
                        default => 'Booking',
                    };
                    $statusColors = ['pending'=>'bg-[#FFC926]/10 text-[#b8910a]','confirmed'=>'bg-[#F96015]/10 text-[#F96015]','in_preparation'=>'bg-[#A31C4E]/10 text-[#A31C4E]','ready_for_delivery'=>'bg-[#9ABC05]/10 text-[#6d8504]','completed'=>'bg-[#18542A]/10 text-[#18542A]','cancelled'=>'bg-[#D52518]/10 text-[#D52518]'];
                    $paymentColors = ['paid'=>'bg-[#9ABC05]/10 text-[#6d8504]','pending'=>'bg-[#FFC926]/10 text-[#b8910a]','unpaid'=>'bg-[#D52518]/10 text-[#D52518]','failed'=>'bg-[#D52518]/10 text-[#D52518]'];
                @endphp
                <div wire:key="mobile-booking-{{ $booking['id'] }}" class="px-4 py-4">
                    <div class="flex items-start justify-between gap-3">
                        <div class="min-w-0">
                            <div class="flex items-center gap-2 flex-wrap mb-1">
                                <a href="{{ route('admin.bookings.show', $booking['reference']) }}" wire:navigate
                                   class="inline-flex items-center px-2 py-0.5 rounded bg-primary/10 text-primary text-[10px] font-bold font-mono hover:bg-primary hover:text-white transition-all">
                                    {{ $booking['reference'] }}
                                </a>
                                <span class="text-[11px] text-base-content/50">{{ $typeLabel }}</span>
                            </div>
                            <p class="text-[14px] font-semibold text-base-content">{{ $booking['customer']['name'] ?? 'Guest' }}</p>
                            <div class="flex items-center gap-1.5 mt-1.5 flex-wrap">
                                <span class="inline-flex px-2 py-0.5 rounded-full text-[10px] font-bold {{ $statusColors[$booking['status'] ?? ''] ?? 'bg-base-200 text-base-content/50' }}">
                                    {{ ucfirst(str_replace('_', ' ', $booking['status'] ?? '')) }}
                                </span>
                                <span class="inline-flex px-2 py-0.5 rounded-full text-[10px] font-bold {{ $paymentColors[$booking['payment_status'] ?? ''] ?? 'bg-base-200 text-base-content/50' }}">
                                    {{ ucfirst($booking['payment_status'] ?? '') }}
                                </span>
                            </div>
                        </div>
                        <div class="text-right shrink-0">
                            <p class="text-[15px] font-bold text-base-content">GH₵{{ number_format($booking['total_amount'], 0) }}</p>
                            <p class="text-[11px] text-base-content/40 mt-0.5">{{ \Carbon\Carbon::parse($booking['created_at'])->format('d M, H:i') }}</p>
                        </div>
                    </div>
                </div>
            @empty
                <div class="px-4 py-12 text-center">
                    <p class="text-[13px] text-base-content/30">No bookings yet</p>
                </div>
            @endforelse
        </div>

        {{-- Desktop: full table (hidden below lg) --}}
        <div class="hidden lg:block overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="border-b border-base-content/5">
                        <th class="px-5 py-3 text-left text-[10px] font-bold uppercase tracking-widest text-base-content/40">Reference</th>
                        <th class="px-5 py-3 text-left text-[10px] font-bold uppercase tracking-widest text-base-content/40">Customer</th>
                        <th class="px-5 py-3 text-left text-[10px] font-bold uppercase tracking-widest text-base-content/40">Type</th>
                        <th class="px-5 py-3 text-left text-[10px] font-bold uppercase tracking-widest text-base-content/40">Amount</th>
                        <th class="px-5 py-3 text-left text-[10px] font-bold uppercase tracking-widest text-base-content/40">Status</th>
                        <th class="px-5 py-3 text-left text-[10px] font-bold uppercase tracking-widest text-base-content/40">Payment</th>
                        <th class="px-5 py-3 text-right text-[10px] font-bold uppercase tracking-widest text-base-content/40">Date</th>
                        <th class="px-5 py-3"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-base-content/5">
                    @forelse($recentActivity as $booking)
                        @php
                            $typeLabel = match($booking['booking_type'] ?? null) {
                                'meal'  => 'Meal Order',
                                'event' => ucfirst($booking['event_type'] ?? 'Event'),
                                default => 'Booking',
                            };
                            $statusColors = ['pending'=>'bg-[#FFC926]/10 text-[#b8910a]','confirmed'=>'bg-[#F96015]/10 text-[#F96015]','in_preparation'=>'bg-[#A31C4E]/10 text-[#A31C4E]','ready_for_delivery'=>'bg-[#9ABC05]/10 text-[#6d8504]','completed'=>'bg-[#18542A]/10 text-[#18542A]','cancelled'=>'bg-[#D52518]/10 text-[#D52518]'];
                            $paymentColors = ['paid'=>'bg-[#9ABC05]/10 text-[#6d8504]','pending'=>'bg-[#FFC926]/10 text-[#b8910a]','unpaid'=>'bg-[#D52518]/10 text-[#D52518]','failed'=>'bg-[#D52518]/10 text-[#D52518]'];
                        @endphp
                        <tr wire:key="table-booking-{{ $booking['id'] }}" class="hover:bg-base-200/30 transition-colors">
                            <td class="px-5 py-3.5">
                                <a href="{{ route('admin.bookings.show', $booking['reference']) }}" wire:navigate
                                   class="inline-flex items-center px-2.5 py-1 rounded-md bg-primary/10 text-primary text-[11px] font-bold hover:bg-primary hover:text-white transition-all font-mono">
                                    {{ $booking['reference'] }}
                                </a>
                            </td>
                            <td class="px-5 py-3.5">
                                <div class="flex items-center gap-2.5">
                                    <div class="w-7 h-7 rounded-full bg-base-200 flex items-center justify-center text-[9px] font-bold text-base-content/50 shrink-0">
                                        {{ strtoupper(substr($booking['customer']['name'] ?? 'G', 0, 2)) }}
                                    </div>
                                    <span class="text-[13px] font-medium text-base-content">{{ $booking['customer']['name'] ?? 'Guest' }}</span>
                                </div>
                            </td>
                            <td class="px-5 py-3.5">
                                <span class="text-[11px] font-semibold text-base-content/60">{{ $typeLabel }}</span>
                            </td>
                            <td class="px-5 py-3.5">
                                <span class="text-[13px] font-bold text-base-content">GH₵{{ number_format($booking['total_amount'], 0) }}</span>
                            </td>
                            <td class="px-5 py-3.5">
                                <span class="inline-flex px-2.5 py-0.5 rounded-full text-[10px] font-bold {{ $statusColors[$booking['status'] ?? ''] ?? 'bg-base-200 text-base-content/50' }}">
                                    {{ ucfirst(str_replace('_', ' ', $booking['status'] ?? '')) }}
                                </span>
                            </td>
                            <td class="px-5 py-3.5">
                                <span class="inline-flex px-2.5 py-0.5 rounded-full text-[10px] font-bold {{ $paymentColors[$booking['payment_status'] ?? ''] ?? 'bg-base-200 text-base-content/50' }}">
                                    {{ ucfirst($booking['payment_status'] ?? '') }}
                                </span>
                            </td>
                            <td class="px-5 py-3.5 text-right">
                                <span class="text-[11px] text-base-content/40">{{ \Carbon\Carbon::parse($booking['created_at'])->format('d M, H:i') }}</span>
                            </td>
                            <td class="px-5 py-3.5 text-right">
                                <a href="{{ route('admin.bookings.show', $booking['reference']) }}" wire:navigate
                                   class="inline-flex items-center gap-1 px-2.5 py-1 rounded-md bg-base-200 text-base-content/50 text-[10px] font-bold hover:bg-primary hover:text-white transition-all whitespace-nowrap">
                                    View
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/></svg>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-5 py-16 text-center">
                                <p class="text-[13px] text-base-content/30 font-medium">No bookings yet</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
