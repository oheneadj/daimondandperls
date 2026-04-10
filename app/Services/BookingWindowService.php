<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Category;
use App\Models\Package;
use Carbon\Carbon;

class BookingWindowService
{
    /**
     * ISO weekday labels (1=Monday … 7=Sunday).
     *
     * @var array<int, string>
     */
    public const DAY_LABELS = [
        1 => 'Monday',
        2 => 'Tuesday',
        3 => 'Wednesday',
        4 => 'Thursday',
        5 => 'Friday',
        6 => 'Saturday',
        7 => 'Sunday',
    ];

    /**
     * Returns the full window status for a category.
     *
     * @return array{
     *     enabled: bool,
     *     open: bool,
     *     cutoff: Carbon|null,
     *     nextDelivery: Carbon|null,
     *     scheduledDelivery: Carbon|null,
     *     deliveryDayLabel: string|null,
     *     cutoffLabel: string|null,
     * }
     */
    public function getStatus(Category $category): array
    {
        if (! $category->hasBookingWindow()) {
            return [
                'enabled' => false,
                'open' => true,
                'cutoff' => null,
                'nextDelivery' => null,
                'scheduledDelivery' => null,
                'deliveryDayLabel' => null,
                'cutoffLabel' => null,
            ];
        }

        $now = Carbon::now();
        [$cutoff, $delivery] = $this->computeWindow($category, $now);
        $open = $now->lt($cutoff);

        // Past cutoff → schedule for the following week's delivery
        $scheduledDelivery = $open ? $delivery->copy() : $delivery->copy()->addWeek();

        return [
            'enabled' => true,
            'open' => $open,
            'cutoff' => $cutoff,
            'nextDelivery' => $delivery,
            'scheduledDelivery' => $scheduledDelivery,
            'deliveryDayLabel' => self::DAY_LABELS[$category->delivery_day] ?? null,
            'cutoffLabel' => self::DAY_LABELS[$category->cutoff_day] ?? null,
        ];
    }

    /**
     * Booking is always allowed. The cutoff only determines which delivery date is assigned.
     * Packages with window_exempt=true (or no category) are also always bookable.
     */
    public function isOpenForPackage(): bool
    {
        return true;
    }

    /**
     * Returns the scheduled delivery date for a windowed package, or null if exempt/no window.
     */
    public function getScheduledDeliveryForPackage(Package $package): ?Carbon
    {
        if ($package->window_exempt || ! $package->category) {
            return null;
        }

        return $this->getStatus($package->category)['scheduledDelivery'] ?? null;
    }

    /**
     * Compute [cutoffCarbon, deliveryCarbon] for the current week cycle.
     *
     * @return array{Carbon, Carbon}
     */
    public function computeWindow(Category $category, Carbon $now): array
    {
        $delivery = $now->copy()->startOfDay();

        // Advance to next occurrence of delivery_day (or stay if today is delivery day)
        if ($delivery->isoWeekday() !== $category->delivery_day) {
            $delivery->next($category->delivery_day);
        }

        // Cutoff is on cutoff_day of the same ISO week as delivery, at cutoff_time
        $cutoff = $delivery->copy()->isoWeekday($category->cutoff_day);

        [$hour, $minute] = array_map('intval', explode(':', substr($category->cutoff_time, 0, 5)));
        $cutoff->setTime($hour, $minute, 0);

        // Cutoff must precede delivery — if same-week placement puts it after, roll it back a week
        if ($cutoff->gte($delivery)) {
            $cutoff->subWeek();
        }

        // If we're already past this delivery date, advance both to next week
        if ($now->gte($delivery->copy()->endOfDay())) {
            $delivery->addWeek();
            $cutoff->addWeek();
        }

        return [$cutoff, $delivery];
    }
}
