<?php

declare(strict_types=1);

namespace App\Livewire\Admin\Reports;

use App\Enums\BookingStatus;
use App\Enums\PaymentGatewayStatus;
use App\Models\Booking;
use App\Models\Payment;
use App\Traits\HasAdminAuthorization;
use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Symfony\Component\HttpFoundation\StreamedResponse;

#[Layout('layouts.admin')]
#[Title('Reports & Analytics')]
class ReportsView extends Component
{
    use HasAdminAuthorization;

    public string $period = 'this_month';

    public string $startDate = '';

    public string $endDate = '';

    public string $sortField = 'event_date';

    public string $sortDirection = 'asc';

    public array $stats = [];

    public array $revenueOverTime = ['labels' => [], 'values' => []];

    public array $statusBreakdown = [];

    public array $eventTypeBreakdown = [];

    public array $revenueByPackage = [];

    public array $paymentSummary = [];

    public array $dailyBookings = [];

    public function mount(): void
    {
        $this->authorizePermission('manage_reports');
        $this->setPeriod('this_month');
    }

    public function updatedPeriod(): void
    {
        if ($this->period !== 'custom') {
            $this->setPeriod($this->period);
        }
        $this->refreshData();
    }

    public function updatedStartDate(): void
    {
        $this->period = 'custom';
        $this->refreshData();
    }

    public function updatedEndDate(): void
    {
        $this->period = 'custom';
        $this->refreshData();
    }

    public function sortBy(string $field): void
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }
        $this->refreshData();
    }

    public function setPeriod(string $period): void
    {
        $this->period = $period;

        match ($period) {
            'today' => [
                $this->startDate = now()->toDateString(),
                $this->endDate = now()->toDateString(),
            ],
            'this_week' => [
                $this->startDate = now()->startOfWeek()->toDateString(),
                $this->endDate = now()->endOfWeek()->toDateString(),
            ],
            'this_month' => [
                $this->startDate = now()->startOfMonth()->toDateString(),
                $this->endDate = now()->toDateString(),
            ],
            default => null,
        };

        $this->refreshData();
    }

    public function refreshData(): void
    {
        $start = Carbon::parse($this->startDate ?: now()->startOfMonth()->toDateString())->startOfDay();
        $end = Carbon::parse($this->endDate ?: now()->toDateString())->endOfDay();

        // 1. Stats
        $bookings = Booking::whereBetween('created_at', [$start, $end])->get();
        $totalBookings = $bookings->count();
        $revenue = Payment::where('status', PaymentGatewayStatus::Successful)
            ->whereBetween('paid_at', [$start, $end])
            ->sum('amount');
        $avgValue = $totalBookings > 0 ? (float) $revenue / $totalBookings : 0;
        $completedCount = $bookings->where('status', BookingStatus::Completed)->count();
        $relevantCount = $bookings->where('status', '!=', BookingStatus::Cancelled)->count();
        $completionRate = $relevantCount > 0 ? ($completedCount / $relevantCount) * 100 : 0;
        $popularPackage = \App\Models\BookingItem::query()
            ->join('bookings', 'booking_items.booking_id', '=', 'bookings.id')
            ->join('packages', 'booking_items.package_id', '=', 'packages.id')
            ->whereBetween('bookings.created_at', [$start, $end])
            ->selectRaw('packages.name, count(*) as count')
            ->groupBy('packages.name')
            ->orderByDesc('count')
            ->first();

        $this->stats = [
            'total_bookings' => $totalBookings,
            'total_revenue' => (float) $revenue,
            'avg_value' => (float) $avgValue,
            'completion_rate' => (float) $completionRate,
            'popular_package' => $popularPackage?->name ?? 'N/A',
        ];

        // 2. Revenue Over Time
        $format = match ($this->period) {
            'today' => 'H:00',
            'this_week', 'this_month' => 'd M',
            default => 'Y-m-d',
        };
        $rotData = Payment::where('status', PaymentGatewayStatus::Successful)
            ->whereBetween('paid_at', [$start, $end])
            ->orderBy('paid_at')
            ->get()
            ->groupBy(fn ($p) => Carbon::parse($p->paid_at)->format($format))
            ->map(fn ($group) => $group->sum('amount'));

        $this->revenueOverTime = [
            'labels' => $rotData->keys()->toArray(),
            'values' => $rotData->values()->toArray(),
        ];

        // 3. Breaksdowns
        $this->statusBreakdown = Booking::whereBetween('created_at', [$start, $end])
            ->selectRaw('status, count(*) as count')
            ->groupBy('status')
            ->get()
            ->mapWithKeys(fn ($item) => [($item->status?->value ?? 'unknown') => $item->count])
            ->toArray();

        $this->eventTypeBreakdown = Booking::whereBetween('created_at', [$start, $end])
            ->selectRaw('event_type, count(*) as count')
            ->groupBy('event_type')
            ->get()
            ->mapWithKeys(fn ($item) => [($item->event_type?->value ?? 'unknown') => $item->count])
            ->toArray();

        $this->revenueByPackage = \App\Models\BookingItem::query()
            ->join('bookings', 'booking_items.booking_id', '=', 'bookings.id')
            ->join('packages', 'booking_items.package_id', '=', 'packages.id')
            ->whereBetween('bookings.created_at', [$start, $end])
            ->selectRaw('packages.name, sum(booking_items.price) as revenue')
            ->groupBy('packages.name')
            ->orderByDesc('revenue')
            ->limit(5)
            ->get()
            ->mapWithKeys(fn ($item) => [$item->name => (float) $item->revenue])
            ->toArray();

        // 4. Summaries
        $payments = Payment::whereBetween('created_at', [$start, $end])->get();
        $this->paymentSummary = [
            'paid' => $payments->where('status', PaymentGatewayStatus::Successful)->sum('amount'),
            'pending' => $payments->where('status', PaymentGatewayStatus::Pending)->sum('amount'),
            'failed' => $payments->where('status', PaymentGatewayStatus::Failed)->sum('amount'),
            'needs_verification' => $payments->where('status', PaymentGatewayStatus::Pending)
                ->where('method', \App\Enums\PaymentMethod::BankTransfer)
                ->sum('amount'),
        ];

        $this->dailyBookings = Booking::whereBetween('created_at', [$start, $end])
            ->selectRaw('DATE(created_at) as date, count(*) as count')
            ->groupBy('date')
            ->orderBy('date')
            ->get()
            ->pluck('count', 'date')
            ->toArray();
    }

    public function exportCsv(): StreamedResponse
    {
        $start = Carbon::parse($this->startDate ?: now()->startOfMonth()->toDateString())->startOfDay();
        $end = Carbon::parse($this->endDate ?: now()->toDateString())->endOfDay();

        $bookings = Booking::query()
            ->with(['customer', 'items.package'])
            ->whereBetween('created_at', [$start, $end])
            ->get();

        $headers = [
            'Content-type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=report_{$this->startDate}_to_{$this->endDate}.csv",
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0',
        ];

        $callback = function () use ($bookings) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['Reference', 'Customer', 'Event Date', 'Package', 'Amount', 'Status', 'Payment']);

            foreach ($bookings as $booking) {
                fputcsv($file, [
                    $booking->reference,
                    $booking->customer?->name ?? 'N/A',
                    $booking->event_date?->format('Y-m-d') ?? 'N/A',
                    $booking->items->first()?->package->name ?? 'N/A',
                    $booking->total_amount,
                    $booking->status?->value ?? 'N/A',
                    $booking->payment_status?->value ?? 'N/A',
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function render(): View
    {
        $upcomingEvents = Booking::query()
            ->with(['customer', 'items.package'])
            ->whereDate('event_date', '>=', now())
            ->whereDate('event_date', '<=', now()->addDays(30))
            ->orderBy($this->sortField, $this->sortDirection)
            ->get();

        $nextWeekStart = now()->addWeek()->startOfWeek();
        $nextWeekEnd = now()->addWeek()->endOfWeek();

        $nextWeekScheduled = \App\Models\BookingItem::query()
            ->with(['booking.customer', 'package'])
            ->whereDate('scheduled_date', '>=', $nextWeekStart)
            ->whereDate('scheduled_date', '<=', $nextWeekEnd)
            ->orderBy('scheduled_date')
            ->get()
            ->groupBy('scheduled_date');

        return view('livewire.admin.reports.view', [
            'upcomingEvents' => $upcomingEvents,
            'nextWeekScheduled' => $nextWeekScheduled,
        ]);
    }
}
