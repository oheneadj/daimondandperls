<?php

declare(strict_types=1);

namespace App\Livewire;

use App\Enums\BookingStatus;
use App\Enums\BookingType;
use App\Enums\PaymentGatewayStatus;
use App\Enums\PaymentStatus;
use App\Models\Booking;
use App\Models\BookingItem;
use App\Models\Customer;
use App\Models\Payment;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.admin')]
#[Title('Command Center')]
class Dashboard extends Component
{
    // ── KPI row ─────────────────────────────────────────────────
    public int $totalBookingsToday = 0;

    public int $totalBookingsMonth = 0;

    public float $revenueMonth = 0;

    public int $needsAttentionCount = 0;

    public int $nextWeekDeliveriesCount = 0;

    // ── Financial ───────────────────────────────────────────────
    public float $totalRevenue = 0;

    public float $projectedRevenue = 0;

    public float $averageOrderValue = 0;

    public float $aovChange = 0;

    public array $revenueTrends = ['labels' => [], 'values' => []];

    // ── Operational ─────────────────────────────────────────────
    public array $bookingTypeMix = [];

    public array $recentActivity = [];

    public array $topVIPs = [];

    public int $newCustomersThisWeek = 0;

    public float $acquisitionRate = 0;

    public function mount(): void
    {
        $this->loadData();
    }

    public function loadData(): void
    {
        $now = now();
        $startOfMonth = $now->copy()->startOfMonth();
        $endOfMonth = $now->copy()->endOfMonth();

        // ── KPIs ────────────────────────────────────────────────
        $this->totalBookingsToday = Booking::whereDate('created_at', today())->count();
        $this->totalBookingsMonth = Booking::whereBetween('created_at', [$startOfMonth, $endOfMonth])->count();

        $this->revenueMonth = (float) Payment::where('status', PaymentGatewayStatus::Successful)
            ->whereBetween('paid_at', [$startOfMonth, $endOfMonth])
            ->sum('amount');

        $this->needsAttentionCount = Booking::whereIn('payment_status', [
            PaymentStatus::Unpaid,
            PaymentStatus::Pending,
        ])->where('status', '!=', BookingStatus::Cancelled)->count();

        $nextWeekStart = $now->copy()->addWeek()->startOfWeek()->toDateString();
        $nextWeekEnd = $now->copy()->addWeek()->endOfWeek()->toDateString();
        $this->nextWeekDeliveriesCount = BookingItem::whereBetween('scheduled_date', [$nextWeekStart, $nextWeekEnd])
            ->distinct('booking_id')
            ->count('booking_id');

        // ── Financial ───────────────────────────────────────────
        $this->totalRevenue = (float) Payment::where('status', PaymentGatewayStatus::Successful)->sum('amount');

        $this->projectedRevenue = (float) Booking::whereIn('status', [
            BookingStatus::Confirmed,
            BookingStatus::InPreparation,
            BookingStatus::ReadyForDelivery,
        ])->whereIn('payment_status', [PaymentStatus::Unpaid, PaymentStatus::Pending])
            ->sum('total_amount');

        // AOV this month vs last month
        $monthlyPaidCount = Payment::where('status', PaymentGatewayStatus::Successful)
            ->whereBetween('paid_at', [$startOfMonth, $endOfMonth])
            ->count();
        $monthlyAov = $monthlyPaidCount > 0 ? $this->revenueMonth / $monthlyPaidCount : 0;
        $this->averageOrderValue = $monthlyAov;

        $lastMonthStart = $now->copy()->subMonth()->startOfMonth();
        $lastMonthEnd = $now->copy()->subMonth()->endOfMonth();
        $lastMonthRevenue = (float) Payment::where('status', PaymentGatewayStatus::Successful)
            ->whereBetween('paid_at', [$lastMonthStart, $lastMonthEnd])
            ->sum('amount');
        $lastMonthCount = Payment::where('status', PaymentGatewayStatus::Successful)
            ->whereBetween('paid_at', [$lastMonthStart, $lastMonthEnd])
            ->count();
        $lastMonthAov = $lastMonthCount > 0 ? $lastMonthRevenue / $lastMonthCount : 0;
        $this->aovChange = $lastMonthAov > 0
            ? (($monthlyAov - $lastMonthAov) / $lastMonthAov) * 100
            : ($monthlyAov > 0 ? 100 : 0);

        // 30-day revenue trend
        $trends = Payment::where('status', PaymentGatewayStatus::Successful)
            ->where('paid_at', '>=', $now->copy()->subDays(29)->startOfDay())
            ->selectRaw('DATE(paid_at) as date, SUM(amount) as total')
            ->groupBy('date')
            ->orderBy('date')
            ->get()
            ->pluck('total', 'date')
            ->toArray();

        $labels = [];
        $values = [];
        for ($i = 29; $i >= 0; $i--) {
            $d = $now->copy()->subDays($i);
            $labels[] = $d->format('d M');
            $values[] = (float) ($trends[$d->format('Y-m-d')] ?? 0);
        }
        $this->revenueTrends = ['labels' => $labels, 'values' => $values];

        // ── Booking type mix ────────────────────────────────────
        $eventCounts = Booking::where('booking_type', BookingType::Event->value)
            ->whereNotNull('event_type')
            ->selectRaw('event_type, COUNT(*) as count')
            ->groupBy('event_type')
            ->get()
            ->mapWithKeys(fn ($item) => [ucfirst($item->event_type?->value ?? 'other') => $item->count])
            ->toArray();

        $mealCount = Booking::where('booking_type', BookingType::Meal->value)->count();
        if ($mealCount > 0) {
            $eventCounts['Meal Orders'] = $mealCount;
        }

        $this->bookingTypeMix = $eventCounts;

        // ── Recent activity ─────────────────────────────────────
        $this->recentActivity = Booking::with('customer')
            ->latest()
            ->limit(8)
            ->get()
            ->toArray();

        // ── Top VIPs ────────────────────────────────────────────
        $this->topVIPs = Customer::withSum('payments', 'amount')
            ->orderByDesc('payments_sum_amount')
            ->limit(10)
            ->get()
            ->filter(fn ($c) => ($c->payments_sum_amount ?? 0) > 0)
            ->take(5)
            ->values()
            ->toArray();

        // ── Customer acquisition ────────────────────────────────
        $this->newCustomersThisWeek = Customer::where('created_at', '>=', $now->copy()->startOfWeek())->count();
        $lastWeekCount = Customer::whereBetween('created_at', [
            $now->copy()->subWeek()->startOfWeek(),
            $now->copy()->subWeek()->endOfWeek(),
        ])->count();
        $this->acquisitionRate = $lastWeekCount > 0
            ? (($this->newCustomersThisWeek - $lastWeekCount) / $lastWeekCount) * 100
            : ($this->newCustomersThisWeek > 0 ? 100 : 0);
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
            BookingStatus::ReadyForDelivery->value => 'Ready',
            BookingStatus::Completed->value => 'Completed',
            BookingStatus::Cancelled->value => 'Cancelled',
        ];

        $colors = [
            BookingStatus::Pending->value => ['bar' => '#FFC926', 'badge' => 'bg-[#FFC926]/10 text-[#b8910a]'],
            BookingStatus::Confirmed->value => ['bar' => '#F96015', 'badge' => 'bg-[#F96015]/10 text-[#F96015]'],
            BookingStatus::InPreparation->value => ['bar' => '#A31C4E', 'badge' => 'bg-[#A31C4E]/10 text-[#A31C4E]'],
            BookingStatus::ReadyForDelivery->value => ['bar' => '#9ABC05', 'badge' => 'bg-[#9ABC05]/10 text-[#6d8504]'],
            BookingStatus::Completed->value => ['bar' => '#18542A', 'badge' => 'bg-[#18542A]/10 text-[#18542A]'],
            BookingStatus::Cancelled->value => ['bar' => '#D52518', 'badge' => 'bg-[#D52518]/10 text-[#D52518]'],
        ];

        $total = array_sum($data);
        $breakdown = [];

        foreach ($statuses as $value => $label) {
            $count = $data[$value] ?? 0;
            $breakdown[] = [
                'label' => $label,
                'count' => $count,
                'percentage' => $total > 0 ? ($count / $total) * 100 : 0,
                'barColor' => $colors[$value]['bar'] ?? '#E5E7EB',
                'badgeClass' => $colors[$value]['badge'] ?? 'bg-base-200 text-base-content/50',
            ];
        }

        return $breakdown;
    }

    public function render(): View
    {
        return view('dashboard');
    }
}
