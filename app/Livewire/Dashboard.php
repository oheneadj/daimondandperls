<?php

declare(strict_types=1);

namespace App\Livewire;

use App\Enums\BookingStatus;
use App\Enums\BookingType;
use App\Enums\PaymentGatewayStatus;
use App\Enums\PaymentStatus;
use App\Models\Booking;
use App\Models\BookingItem;
use App\Models\Payment;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.admin')]
#[Title('Command Center')]
class Dashboard extends Component
{
    // ── KPI row ─────────────────────────────────────────────────
    public int $needsAttentionCount = 0;

    public int $todayDeliveriesCount = 0;

    public int $nextWeekDeliveriesCount = 0;

    public int $unreadMessagesCount = 0;

    // ── Revenue chart side cards ─────────────────────────────────
    public int $thisWeekBookings = 0;

    public float $thisWeekBookingsChange = 0;

    public float $cancellationRate = 0;

    public float $cancellationRateChange = 0;

    public int $pendingEventQuotes = 0;

    // ── Revenue chart ────────────────────────────────────────────
    public float $revenueMonth = 0;

    public float $projectedRevenue = 0;

    public float $totalRevenue = 0;

    public array $revenueTrends = ['labels' => [], 'values' => []];

    // ── Operations ───────────────────────────────────────────────
    public array $todaySchedule = [];

    public array $recentActivity = [];

    public function mount(): void
    {
        $this->loadData();
    }

    public function loadData(): void
    {
        $now = now();
        $startOfMonth = $now->copy()->startOfMonth();
        $endOfMonth = $now->copy()->endOfMonth();

        // ── KPI: Needs Attention ─────────────────────────────────
        $this->needsAttentionCount = Booking::whereIn('payment_status', [
            PaymentStatus::Unpaid,
            PaymentStatus::Pending,
        ])->where('status', '!=', BookingStatus::Cancelled)->count();

        // ── KPI: Today's Deliveries ──────────────────────────────
        $this->todayDeliveriesCount = BookingItem::whereDate('scheduled_date', today())
            ->whereHas('booking', fn ($q) => $q->where('status', '!=', BookingStatus::Cancelled))
            ->distinct('booking_id')
            ->count('booking_id');

        // ── KPI: Next Week Deliveries ────────────────────────────
        $nextWeekStart = $now->copy()->addWeek()->startOfWeek()->toDateString();
        $nextWeekEnd = $now->copy()->addWeek()->endOfWeek()->toDateString();
        $this->nextWeekDeliveriesCount = BookingItem::whereBetween('scheduled_date', [$nextWeekStart, $nextWeekEnd])
            ->distinct('booking_id')
            ->count('booking_id');

        // ── KPI: Unread Contact Messages ─────────────────────────
        // Shared 60s cache with the admin sidebar so both components hit the DB once per minute.
        $this->unreadMessagesCount = Cache::remember('contact_messages.new_count', 60, fn () => DB::table('contact_messages')->where('status', 'new')->count());

        // ── Side cards: Week comparison + Month cancellation rate ─
        // One query covers all four date-range counts via conditional aggregation.
        $thisWeekStart = $now->copy()->startOfWeek();
        $thisWeekEnd = $now->copy()->endOfWeek();
        $lastWeekStart = $now->copy()->subWeek()->startOfWeek();
        $lastWeekEnd = $now->copy()->subWeek()->endOfWeek();
        $lastMonthStart = $now->copy()->subMonth()->startOfMonth();
        $lastMonthEnd = $now->copy()->subMonth()->endOfMonth();

        $counts = Booking::withTrashed(false)
            ->whereBetween('created_at', [$lastMonthStart, $endOfMonth])
            ->selectRaw('
                SUM(CASE WHEN created_at BETWEEN ? AND ? THEN 1 ELSE 0 END) as this_week,
                SUM(CASE WHEN created_at BETWEEN ? AND ? THEN 1 ELSE 0 END) as last_week,
                SUM(CASE WHEN created_at BETWEEN ? AND ? THEN 1 ELSE 0 END) as month_total,
                SUM(CASE WHEN created_at BETWEEN ? AND ? AND status = ? THEN 1 ELSE 0 END) as month_cancelled,
                SUM(CASE WHEN created_at BETWEEN ? AND ? THEN 1 ELSE 0 END) as prev_month_total,
                SUM(CASE WHEN created_at BETWEEN ? AND ? AND status = ? THEN 1 ELSE 0 END) as prev_month_cancelled
            ', [
                $thisWeekStart, $thisWeekEnd,
                $lastWeekStart, $lastWeekEnd,
                $startOfMonth, $endOfMonth,
                $startOfMonth, $endOfMonth, BookingStatus::Cancelled->value,
                $lastMonthStart, $lastMonthEnd,
                $lastMonthStart, $lastMonthEnd, BookingStatus::Cancelled->value,
            ])
            ->first();

        $this->thisWeekBookings = (int) ($counts->this_week ?? 0);
        $lastWeekBookings = (int) ($counts->last_week ?? 0);
        $this->thisWeekBookingsChange = $lastWeekBookings > 0
            ? round((($this->thisWeekBookings - $lastWeekBookings) / $lastWeekBookings) * 100, 1)
            : ($this->thisWeekBookings > 0 ? 100.0 : 0.0);

        $monthTotal = (int) ($counts->month_total ?? 0);
        $monthCancelled = (int) ($counts->month_cancelled ?? 0);
        $this->cancellationRate = $monthTotal > 0
            ? round(($monthCancelled / $monthTotal) * 100, 1)
            : 0.0;

        $lastMonthTotal = (int) ($counts->prev_month_total ?? 0);
        $lastMonthCancelled = (int) ($counts->prev_month_cancelled ?? 0);
        $lastMonthRate = $lastMonthTotal > 0
            ? round(($lastMonthCancelled / $lastMonthTotal) * 100, 1)
            : 0.0;

        // Positive change = rate went up (bad). Negative = rate went down (good).
        $this->cancellationRateChange = round($this->cancellationRate - $lastMonthRate, 1);

        // ── Side card: Pending Event Quotes ──────────────────────
        // Event bookings confirmed/pending but still unpaid — awaiting customer payment.
        $this->pendingEventQuotes = Booking::where('booking_type', BookingType::Event)
            ->whereIn('status', [BookingStatus::Pending, BookingStatus::Confirmed])
            ->whereIn('payment_status', [PaymentStatus::Unpaid, PaymentStatus::Pending])
            ->count();

        // ── Revenue chart ─────────────────────────────────────────
        $this->totalRevenue = (float) Payment::where('status', PaymentGatewayStatus::Successful)->sum('amount');

        $this->revenueMonth = (float) Payment::where('status', PaymentGatewayStatus::Successful)
            ->whereBetween('paid_at', [$startOfMonth, $endOfMonth])
            ->sum('amount');

        $this->projectedRevenue = (float) Booking::whereIn('status', [
            BookingStatus::Confirmed,
            BookingStatus::InPreparation,
            BookingStatus::ReadyForDelivery,
        ])->whereIn('payment_status', [PaymentStatus::Unpaid, PaymentStatus::Pending])
            ->sum('total_amount');

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

        // ── Today's Delivery Schedule ─────────────────────────────
        $this->todaySchedule = BookingItem::with(['booking.customer', 'package'])
            ->whereDate('scheduled_date', today())
            ->whereHas('booking', fn ($q) => $q->where('status', '!=', BookingStatus::Cancelled))
            ->orderBy('scheduled_date')
            ->get()
            ->toArray();

        // ── Recent Bookings ───────────────────────────────────────
        $this->recentActivity = Booking::with('customer')
            ->latest()
            ->limit(8)
            ->get()
            ->toArray();
    }

    /**
     * Order pipeline breakdown for the current month.
     */
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
