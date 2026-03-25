<?php

namespace App\Livewire;

use App\Enums\BookingStatus;
use App\Enums\PaymentStatus;
use App\Models\Booking;
use App\Models\Customer;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.admin')]
#[Title('Command Center')]
class Dashboard extends Component
{
    public int $totalBookingsToday = 0;

    public int $upcomingEvents = 0;

    public int $pendingPayments = 0;

    public int $completedBookings = 0;

    // Advanced Metrics
    public float $totalRevenue = 0;

    public float $projectedRevenue = 0;

    public float $averageOrderValue = 0;

    public array $recentActivity = [];

    public array $revenueTrends = [];

    public array $eventTypeDistribution = [];

    public array $eventHeatmap = [];

    public array $highPriorityFollowUps = [];

    public array $expiringPrepWindow = [];

    public array $topVIPs = [];

    public float $acquisitionRate = 0;

    // Admin Merged Stats
    public int $totalBookingsMonth = 0;

    public float $revenueMonth = 0;

    public int $activePackagesCount = 0;

    public int $unpaidBookingsCount = 0;

    public function mount(): void
    {
        $this->loadData();
    }

    private function loadData(): void
    {
        $this->totalBookingsToday = Booking::whereDate('created_at', today())->count();

        $this->upcomingEvents = Booking::whereDate('event_date', '>=', today())->count();

        $this->pendingPayments = Booking::whereIn('payment_status', [
            PaymentStatus::Unpaid,
            PaymentStatus::Pending,
        ])->count();

        $this->completedBookings = Booking::where('status', BookingStatus::Completed)->count();

        // 1. Financial Pulse
        $this->totalRevenue = Booking::where('payment_status', PaymentStatus::Paid)
            ->sum('total_amount');

        $this->projectedRevenue = Booking::whereIn('status', [
            BookingStatus::Confirmed,
            BookingStatus::InPreparation,
        ])
            ->whereIn('payment_status', [PaymentStatus::Unpaid, PaymentStatus::Pending])
            ->sum('total_amount');

        $paidCount = Booking::where('payment_status', PaymentStatus::Paid)->count();
        $this->averageOrderValue = $paidCount > 0 ? $this->totalRevenue / $paidCount : 0;

        // Revenue Trends (30 Days)
        $trends = Booking::where('payment_status', PaymentStatus::Paid)
            ->where('created_at', '>=', now()->subDays(29)->startOfDay())
            ->selectRaw('DATE(created_at) as date, SUM(total_amount) as total')
            ->groupBy('date')
            ->orderBy('date')
            ->get()
            ->pluck('total', 'date')
            ->toArray();

        $this->revenueTrends = [];
        for ($i = 29; $i >= 0; $i--) {
            $date = now()->subDays($i)->format('Y-m-d');
            $this->revenueTrends[$date] = $trends[$date] ?? 0;
        }

        // 2. Operational Intelligence
        // Event Heatmap (Next 30 Days)
        $heatmapData = Booking::where('event_date', '>=', today())
            ->where('event_date', '<=', now()->addDays(29)->endOfDay())
            ->where('status', '!=', BookingStatus::Cancelled)
            ->selectRaw('DATE(event_date) as date, COUNT(*) as count')
            ->groupBy('date')
            ->get()
            ->pluck('count', 'date')
            ->toArray();

        $this->eventHeatmap = [];
        for ($i = 0; $i < 30; $i++) {
            $date = now()->addDays($i)->format('Y-m-d');
            $this->eventHeatmap[$date] = $heatmapData[$date] ?? 0;
        }

        // Live Activity Feed
        $this->recentActivity = Booking::with('customer')
            ->latest()
            ->limit(5)
            ->get()
            ->toArray();

        // Service Pop Mix
        $this->eventTypeDistribution = Booking::selectRaw('event_type, COUNT(*) as count')
            ->groupBy('event_type')
            ->get()
            ->mapWithKeys(function ($item) {
                return [($item->event_type?->value ?? 'other') => $item->count];
            })
            ->toArray();

        // 3. Immediate Action Items
        $this->highPriorityFollowUps = Booking::with('customer')
            ->whereIn('payment_status', [PaymentStatus::Unpaid, PaymentStatus::Pending])
            ->orderByDesc('total_amount')
            ->limit(5)
            ->get()
            ->toArray();

        $this->expiringPrepWindow = Booking::with('customer')
            ->whereBetween('event_date', [now(), now()->addHours(48)])
            ->where('status', '!=', BookingStatus::Completed)
            ->orderBy('event_date')
            ->get()
            ->toArray();

        // 4. Customer Insights
        $this->topVIPs = Customer::withSum('payments', 'amount')
            ->orderByDesc('payments_sum_amount')
            ->limit(5)
            ->get()
            ->toArray();

        $thisWeeksCount = Customer::where('created_at', '>=', now()->startOfWeek())->count();
        $lastWeeksCount = Customer::whereBetween('created_at', [now()->subWeek()->startOfWeek(), now()->subWeek()->endOfWeek()])->count();

        $this->acquisitionRate = $lastWeeksCount > 0
            ? (($thisWeeksCount - $lastWeeksCount) / $lastWeeksCount) * 100
            : ($thisWeeksCount > 0 ? 100 : 0);

        // 5. Admin Merged Stats
        $startOfMonth = now()->startOfMonth();
        $endOfMonth = now()->endOfMonth();

        $this->totalBookingsMonth = Booking::whereBetween('created_at', [$startOfMonth, $endOfMonth])->count();

        $this->revenueMonth = (float) \App\Models\Payment::where('status', \App\Enums\PaymentGatewayStatus::Successful)
            ->whereBetween('paid_at', [$startOfMonth, $endOfMonth])
            ->sum('amount');

        $this->activePackagesCount = \App\Models\Package::where('is_active', true)->count();

        $this->unpaidBookingsCount = Booking::where('payment_status', PaymentStatus::Unpaid)->count();
    }

    public function getStatusBreakdownProperty(): array
    {
        $startOfMonth = now()->startOfMonth();
        $endOfMonth = now()->endOfMonth();

        $data = Booking::whereBetween('created_at', [$startOfMonth, $endOfMonth])
            ->selectRaw('status, count(*) as count')
            ->groupBy('status')
            ->get()
            ->mapWithKeys(fn ($item) => [$item->status->value => $item->count])
            ->toArray();

        $statuses = [
            BookingStatus::Pending->value => 'Pending',
            BookingStatus::Confirmed->value => 'Confirmed',
            BookingStatus::InPreparation->value => 'In Prep',
            BookingStatus::Completed->value => 'Completed',
            BookingStatus::Cancelled->value => 'Cancelled',
        ];

        $total = array_sum($data);
        $breakdown = [];

        foreach ($statuses as $value => $label) {
            $count = $data[$value] ?? 0;
            $percentage = $total > 0 ? ($count / $total) * 100 : 0;

            $breakdown[] = [
                'label' => $label,
                'count' => $count,
                'percentage' => $percentage,
                'color' => $this->getStatusColor($value),
            ];
        }

        return $breakdown;
    }

    private function getStatusColor(string $status): string
    {
        return match ($status) {
            BookingStatus::Pending->value => 'bg-dp-warning',
            BookingStatus::Confirmed->value => 'bg-dp-info',
            BookingStatus::InPreparation->value => 'bg-dp-rose',
            BookingStatus::Completed->value => 'bg-dp-success',
            BookingStatus::Cancelled->value => 'bg-dp-text-muted',
            default => 'bg-dp-pearl-mid',
        };
    }

    public function render()
    {
        $this->loadData();
        return view('dashboard');
    }
}
