<div class="space-y-10 pb-10 ">
    {{-- Header Section --}}
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-4 md:gap-6">
        <div class="min-w-0">
            <h1 class="text-[24px] md:text-[32px] font-semibold text-base-content leading-tight">
                {{ __('Dashboard') }} <span class="text-[#FFC926] mx-1">Hello</span> <span class="text-primary truncate">{{ auth()->user()->displayName() }}</span>
            </h1>
            <p class="text-[13px] md:text-[15px] text-base-content/60 mt-2">
                {{ __('Your operations at a glance for') }} <span class="italic font-medium">Diamonds & Pearls</span>.
            </p>
        </div>
        <div class="flex items-center gap-3 flex-shrink-0">
            <div class="flex items-center gap-2.5 text-[10px] sm:text-[11px] font-bold text-white bg-neutral px-4 sm:px-5 py-2.5 sm:py-3 rounded-full border border-base-content/5 shadow-sm uppercase tracking-widest">
                <span class="relative flex h-2 w-2">
                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-success opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-2 w-2 bg-success"></span>
                </span>
                {{ now()->format('d M Y') }}
            </div>
            <x-button variant="ghost" size="icon" wire:click="$refresh" wire:loading.class="animate-spin" wire:target="$refresh" class="rounded-full shadow-sm bg-white">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 opacity-40 hover:opacity-100 transition-opacity" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                </svg>
            </x-button>
        </div>
    </div>

    {{-- Monthly Performance Snapshots --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <x-stat-card 
            title="Monthly Bookings" 
            value="{{ $totalBookingsMonth }}"
            color="primary"
        >
            <x-slot:icon>
                @include('layouts.partials.icons.clipboard-document-list', ['class' => 'w-5 h-5'])
            </x-slot:icon>
        </x-stat-card>

        <x-stat-card 
            title="Monthly Revenue" 
            value="GH₵{{ number_format($revenueMonth, 2) }}"
            color="accent"
        >
            <x-slot:icon>
                @include('layouts.partials.icons.credit-card', ['class' => 'w-5 h-5'])
            </x-slot:icon>
        </x-stat-card>

        <x-stat-card 
            title="Active Packages" 
            value="{{ $activePackagesCount }}"
            color="success"
        >
            <x-slot:icon>
                @include('layouts.partials.icons.cake', ['class' => 'w-5 h-5'])
            </x-slot:icon>
        </x-stat-card>

        <x-stat-card 
            title="Unpaid Slips" 
            value="{{ $unpaidBookingsCount }}"
            color="error"
        >
            <x-slot:icon>
                @include('layouts.partials.icons.exclamation-triangle-solid', ['class' => 'w-5 h-5'])
            </x-slot:icon>
        </x-stat-card>
    </div>

    {{-- Financial Pulse: The Revenue Engine --}}
    <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
        {{-- Main Chart Area (3 cols) --}}
        <div class="lg:col-span-3 bg-white border border-base-content/5 rounded-xl shadow-sm overflow-hidden flex flex-col">
            <div class="p-5 sm:p-8 pb-4 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div>
                    <p class="text-[11px] font-bold uppercase tracking-widest text-base-content/60 mb-1">{{ __('Financial Intelligence') }}</p>
                    <h2 class="text-[20px] sm:text-[24px] font-semibold text-base-content tracking-tight">{{ __('Business Performance') }}</h2>
                </div>
                <div class="flex gap-4 sm:gap-6">
                    <div class="sm:text-right">
                        <p class="text-[9px] font-bold uppercase tracking-widest text-base-content/60 opacity-60">{{ __('Booked (Actual)') }}</p>
                        <p class="text-[14px] sm:text-[16px] font-bold text-success">GH₵{{ number_format($totalRevenue, 2) }}</p>
                    </div>
                    <div class="h-8 w-px border-base-content/5 border-l"></div>
                    <div class="sm:text-right">
                        <p class="text-[9px] font-bold uppercase tracking-widest text-base-content/60 opacity-60">{{ __('Projected') }}</p>
                        <p class="text-[14px] sm:text-[16px] font-bold text-[#18542A]">GH₵{{ number_format($projectedRevenue, 2) }}</p>
                    </div>
                </div>
            </div>
            
            <div class="h-[300px] w-full mt-auto px-4" x-data="{
                init() {
                    const ctx = document.getElementById('revenueChart30').getContext('2d');
                    
                    {{-- Carrot (Primary) and Kiwi (Success) gradients --}}
                    const gradient = ctx.createLinearGradient(0, 0, 0, 300);
                    gradient.addColorStop(0, 'rgba(249, 96, 21, 0.12)');
                    gradient.addColorStop(1, 'rgba(249, 96, 21, 0)');

                    new Chart(ctx, {
                        type: 'line',
                        data: {
                            labels: @js(collect(array_keys($revenueTrends))->map(fn($d) => \Carbon\Carbon::parse($d)->format('d M'))),
                            datasets: [
                                {
                                    label: 'Actual Revenue',
                                    data: @js(array_values($revenueTrends)),
                                    borderColor: '#F96015',
                                    backgroundColor: gradient,
                                    fill: true,
                                    borderWidth: 3,
                                    tension: 0.4,
                                    pointRadius: 0,
                                    pointHoverRadius: 6,
                                    pointHoverBackgroundColor: '#F96015',
                                    pointHoverBorderColor: '#FFFFFF',
                                    pointHoverBorderWidth: 3
                                },
                                {
                                    label: 'Projected',
                                    data: @js(array_values($revenueTrends)), {{-- Placeholder --}}
                                    borderColor: '#9ABC05',
                                    borderWidth: 2,
                                    borderDash: [5, 5],
                                    fill: false,
                                    tension: 0.4,
                                    pointRadius: 0,
                                    pointHoverRadius: 4,
                                    pointHoverBackgroundColor: '#9ABC05',
                                    pointHoverBorderColor: '#FFFFFF',
                                    pointHoverBorderWidth: 2
                                }
                            ]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: { 
                                legend: { display: false }, 
                                tooltip: { 
                                    backgroundColor: '#1C1A18',
                                    titleFont: { family: 'Outfit', size: 11, weight: 'bold' },
                                    bodyFont: { family: 'Outfit', size: 13 },
                                    padding: 12,
                                    cornerRadius: 8,
                                    displayColors: false,
                                    mode: 'index', 
                                    intersect: false 
                                } 
                            },
                            scales: {
                                y: { display: false, beginAtZero: true },
                                x: { 
                                    grid: { display: false }, 
                                    ticks: { 
                                        font: { size: 9, family: 'Outfit', weight: 'bold' }, 
                                        color: '#7A746C', 
                                        maxRotation: 0, 
                                        autoSkip: true, 
                                        maxTicksLimit: 10 
                                    } 
                                }
                            }
                        }
                    });
                }
            }">
                <canvas id="revenueChart30"></canvas>
            </div>
        </div>

        {{-- Metric Sidebar (1 col) --}}
        <div class="space-y-6">
            <x-stat-card 
                title="Average Sale" 
                value="GH₵{{ number_format($averageOrderValue, 2) }}"
                trend="up"
                trendValue="4.2%"
                subtext="Optimized against budget tier"
                color="primary"
            >
                <x-slot:icon>
                    @include('layouts.partials.icons.chart-bar-square', ['class' => 'w-5 h-5'])
                </x-slot:icon>
            </x-stat-card>

            <x-stat-card 
                title="To Do" 
                value="{{ $pendingPayments }}"
                subtext="Action required for verification"
                color="warning"
            >
                <x-slot:icon>
                    @include('layouts.partials.icons.information-circle-solid', ['class' => 'w-5 h-5'])
                </x-slot:icon>
            </x-stat-card>

            <x-stat-card 
                title="New Clients" 
                value="{{ number_format($acquisitionRate, 1) }}%"
                trend="{{ $acquisitionRate >= 0 ? 'up' : 'down' }}"
                trendValue="{{ abs(round($acquisitionRate)) }}%"
                subtext="New client conversation rate"
                color="info"
            >
                <x-slot:icon>
                    @include('layouts.partials.icons.squares-2x2', ['class' => 'w-5 h-5'])
                </x-slot:icon>
            </x-stat-card>
        </div>
    </div>

    {{-- Operational Intelligence & Actionable Insights --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        {{-- Pipeline Analysis --}}
        <div class="bg-white border border-base-content/5 rounded-xl shadow-sm p-8 flex flex-col">
            <div class="flex items-center justify-between mb-8">
                <div>
                     <p class="text-[11px] font-bold uppercase tracking-widest text-base-content/40 mb-1">{{ __('Pipeline Analysis') }}</p>
                     <h3 class="text-[20px] font-semibold text-base-content tracking-tight">{{ __('Order Status') }}</h3>
                </div>
                <div class="w-10 h-10 rounded-xl bg-[#F96015]/10 flex items-center justify-center">
                    @include('layouts.partials.icons.chart-bar-square', ['class' => 'w-5 h-5 text-[#F96015]'])
                </div>
            </div>
            <div class="space-y-5 flex-1">
                @php
                    $statusColors = [
                        'bg-[#F96015]', // Carrot
                        'bg-[#FFC926]', // Sunshine
                        'bg-[#9ABC05]', // Kiwi
                        'bg-[#18542A]', // Forest Green
                        'bg-[#D52518]', // Tomato
                    ];
                    $badgeColors = [
                        'bg-[#F96015]/10 text-[#F96015]',
                        'bg-[#FFC926]/10 text-[#b8910a]',
                        'bg-[#9ABC05]/10 text-[#6d8504]',
                        'bg-[#18542A]/10 text-[#18542A]',
                        'bg-[#D52518]/10 text-[#D52518]',
                    ];
                @endphp
                @foreach($this->statusBreakdown as $index => $stat)
                    <div wire:key="status-{{ $stat['label'] }}" class="group">
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-[12px] font-bold text-base-content">{{ $stat['label'] }}</span>
                            <span class="text-[11px] font-bold px-2.5 py-0.5 rounded-full {{ $badgeColors[$index % 5] }}">{{ $stat['count'] }}</span>
                        </div>
                        <div class="h-2.5 w-full bg-base-200 rounded-full overflow-hidden">
                            <div 
                                class="h-full {{ $statusColors[$index % 5] }} rounded-full transition-all duration-1000 group-hover:opacity-80" 
                                style="width: {{ $stat['percentage'] }}%"
                            ></div>
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="mt-6 pt-5 border-t border-base-content/5">
                <p class="text-[11px] text-base-content/40 text-center">{{ __('Current month catering lifecycle') }}</p>
            </div>
        </div>

        {{-- Popularity Mix (Donut Chart) --}}
        <div class="bg-white border border-base-content/5 rounded-xl shadow-sm p-8 flex flex-col">
             <div class="flex items-center justify-between mb-8">
                <div>
                     <p class=" text-[11px] font-bold uppercase tracking-widest text-base-content/60 mb-1">{{ __('Marketing Mix') }}</p>
                     <h3 class=" text-[20px] font-semibold text-base-content tracking-tight">{{ __('Service Popularity') }}</h3>
                </div>
            </div>
            <div class="h-[180px] w-full relative" x-data="{
                init() {
                    const ctx = document.getElementById('popMixChart').getContext('2d');
                    new Chart(ctx, {
                        type: 'doughnut',
                        data: {
                            labels: @js(array_map('ucfirst', array_keys($eventTypeDistribution))),
                            datasets: [{
                                data: @js(array_values($eventTypeDistribution)),
                                backgroundColor: [
                                    '#FFC926', // Sunshine
                                    '#9ABC05', // Kiwi
                                    '#F96015', // Crisp Carrot
                                    '#D52518', // Tomato Burst
                                    '#18542A'  // Forest Green
                                ],
                                borderWidth: 0,
                                hoverOffset: 8
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            cutout: '82%',
                            plugins: { legend: { display: false } }
                        }
                    });
                }
            }">
                <canvas id="popMixChart"></canvas>
                <div class="absolute inset-0 flex flex-col items-center justify-center pointer-events-none">
                    <span class=" text-[28px] font-bold text-base-content">{{ collect($eventTypeDistribution)->sum() }}</span>
                    <span class=" text-[9px] font-bold uppercase tracking-[0.2em] text-base-content/60 opacity-60">{{ __('Bookings') }}</span>
                </div>
            </div>
            <div class="mt-10 space-y-3.5">
                @php $colors = ['#FFC926', '#9ABC05', '#F96015', '#D52518', '#18542A']; $i=0; @endphp
                @foreach($eventTypeDistribution as $type => $count)
                    <div wire:key="dist-{{ $type }}" class="flex items-center justify-between">
                         <div class="flex items-center gap-2.5">
                            <div class="w-2 h-2 rounded-full" style="background-color: {{ $colors[$i % 5] }}"></div>
                            <span class=" text-[11px] font-bold text-base-content/60 uppercase tracking-widest">{{ $type }}</span>
                         </div>
                         <span class=" text-[11px] font-bold text-base-content">{{ round(($count / collect($eventTypeDistribution)->sum()) * 100) }}%</span>
                    </div>
                    @php $i++; @endphp
                @endforeach
            </div>
        </div>

        {{-- Top VIPs Section --}}
        @php
            $rankColors = [
                0 => 'bg-primary text-black shadow-[0_0_12px_rgba(255,201,38,0.25)]',
                1 => 'bg-accent text-white shadow-[0_0_12px_rgba(249,96,21,0.25)]',
                2 => 'bg-success text-white shadow-[0_0_12px_rgba(154,188,5,0.25)]',
                3 => 'bg-error text-white shadow-[0_0_12px_rgba(213,37,24,0.25)]',
                4 => 'bg-[#18542A] text-white shadow-[0_0_12px_rgba(24,84,42,0.25)]',
            ];
            $rankPills = [
                0 => 'bg-primary/10 text-primary border-primary/20',
                1 => 'bg-accent/10 text-accent border-accent/20',
                2 => 'bg-success/10 text-success border-success/20',
                3 => 'bg-error/10 text-error border-error/20',
                4 => 'bg-[#18542A]/10 text-[#18542A] border-[#18542A]/20',
            ];
        @endphp
        <div class="bg-white border border-base-content/5 rounded-xl shadow-sm flex flex-col overflow-hidden">
             <div class="p-8 pb-5">
                <p class=" text-[11px] font-bold uppercase tracking-widest text-base-content/60 mb-1">{{ __('CRM Insights') }}</p>
                <h3 class=" text-[20px] font-semibold text-base-content tracking-tight">{{ __('Best Customers') }}</h3>
            </div>
            <div class="divide-y divide-base-200 flex-1">
                @forelse($topVIPs as $index => $vip)
                    <div wire:key="vip-{{ $index }}" class="px-8 py-5 flex items-center justify-between group hover:bg-base-200 transition-all duration-300">
                        <div class="flex items-center gap-4">
                            <div class="w-11 h-11 rounded-full {{ $rankColors[$index % 5] }} flex items-center justify-center text-[12px] font-black group-hover:scale-110 transition-transform">
                                {{ strtoupper(substr($vip['name'], 0, 2)) }}
                            </div>
                            <div>
                                <h4 class=" text-[14px] font-bold text-base-content group-hover:text-primary transition-colors">{{ $vip['name'] }}</h4>
                                <div class="mt-1 flex items-center gap-2">
                                    <span class="px-2 py-0.5 rounded-full border text-[9px] font-bold uppercase tracking-wider {{ $rankPills[$index % 5] }}">
                                        LTV: GH₵{{ number_format($vip['payments_sum_amount'], 2) }}
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="flex flex-col items-end gap-1">
                             <span class="text-[10px] font-black italic opacity-20 group-hover:opacity-100 group-hover:text-primary transition-all">#{{ $index + 1 }}</span>
                             @include('layouts.partials.icons.check-circle-solid', ['class' => 'w-5 h-5 text-primary/30 group-hover:text-primary group-hover:scale-110 transition-all'])
                        </div>
                    </div>
                @empty
                     <div class="p-12 text-center opacity-40 italic  text-[12px]">No VIP data detected</div>
                @endforelse
            </div>
            <div class="p-4 bg-base-200-mid/30 border-t border-base-content/5 flex justify-center">
                 <x-button variant="ghost" size="sm" class="w-full text-base-content/60 hover:text-primary font-bold" href="{{ route('admin.customers.index') }}" wire:navigate>
                    {{ __('View Full Directory') }} &rarr;
                 </x-button>
            </div>
        </div>
    </div>

    {{-- Management Center & Operations Log --}}
    <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
        {{-- Management Center --}}


        {{-- Log: Recent Activities --}}
        <div class="lg:col-span-3">
             <x-data-table :headers="['Reference', 'Customer', 'Service', 'Amount', 'Status', 'Actions']">
                @forelse($recentActivity as $booking)
                    <tr wire:key="booking-{{ $booking['id'] }}" class="group hover:bg-base-200/50 transition-colors duration-150 border-b border-base-content/5 last:border-0  text-[13px]">
                        <td class="px-5 py-4">
                            <span class="inline-flex items-center px-2.5 py-1 rounded-lg bg-primary text-white border border-primary/30 text-[12px] font-bold shadow-sm whitespace-nowrap">
                                {{ $booking['reference'] }}
                            </span>
                        </td>
                        <td class="px-5 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-7 h-7 rounded-full bg-base-200-mid text-base-content/60 flex items-center justify-center text-[9px] font-bold">
                                    {{ strtoupper(substr($booking['customer']['name'] ?? 'GU', 0, 2)) }}
                                </div>
                                <span class="text-base-content/80 font-medium">{{ $booking['customer']['name'] ?? 'Guest' }}</span>
                            </div>
                        </td>
                        <td class="px-5 py-4">
                            <x-badge :type="strtolower($booking['event_type']) ?? 'other'">{{ $booking['event_type'] ?? 'Other' }}</x-badge>
                        </td>
                        <td class="px-5 py-4">
                            <span class="font-bold text-base-content">GH₵{{ number_format($booking['total_amount'], 2) }}</span>
                        </td>
                        <td class="px-5 py-4">
                            <x-badge :type="$booking['status']" dot>{{ ucfirst($booking['status']) }}</x-badge>
                        </td>
                        <td class="px-5 py-4 text-right">
                            <div class="flex items-center justify-end gap-2">
                                <x-button variant="ghost" size="icon" icon="magnifying-glass" title="View Details" />
                                <x-button variant="ghost" size="icon" icon="cog-6-tooth" title="Edit Booking" />
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-5 py-12 text-center">
                            <x-empty-state 
                                icon="table-cells" 
                                title="Nothing Yet" 
                                description="No bookings added lately. Create one to get started."
                            />
                        </td>
                    </tr>
                @endforelse
             </x-data-table>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
@endpush