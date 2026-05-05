<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\BookingWindow;
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
     * Returns the full window status for a BookingWindow.
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
    public function getStatus(BookingWindow $window): array
    {
        $now = Carbon::now();
        [$cutoff, $delivery] = $this->computeWindow($window, $now);
        $open = $now->lt($cutoff);

        // Past cutoff → schedule for the following week's delivery
        $scheduledDelivery = $open ? $delivery->copy() : $delivery->copy()->addWeek();

        return [
            'enabled' => true,
            'open' => $open,
            'cutoff' => $cutoff,
            'nextDelivery' => $delivery,
            'scheduledDelivery' => $scheduledDelivery,
            'deliveryDayLabel' => self::DAY_LABELS[$window->delivery_day] ?? null,
            'cutoffLabel' => self::DAY_LABELS[$window->cutoff_day] ?? null,
        ];
    }

    /**
     * Returns the active window for a package — the one whose next cutoff is soonest.
     * Returns null if the package has no windows (always bookable, no delivery date).
     */
    public function getActiveWindow(Package $package): ?BookingWindow
    {
        $windows = $package->relationLoaded('bookingWindows')
            ? $package->bookingWindows
            : $package->bookingWindows()->get();

        if ($windows->isEmpty()) {
            return null;
        }

        $now = Carbon::now();

        return $windows->sortBy(function (BookingWindow $window) use ($now) {
            [$cutoff] = $this->computeWindow($window, $now);

            // If cutoff is in the past, use next week's cutoff for ordering
            if ($cutoff->lte($now)) {
                $cutoff->addWeek();
            }

            return $cutoff->timestamp;
        })->first();
    }

    /**
     * Returns the scheduled delivery date for a package via its active window.
     * Returns null if the package has no windows (always orderable, no date shown).
     */
    public function getScheduledDeliveryForPackage(Package $package): ?Carbon
    {
        $activeWindow = $this->getActiveWindow($package);

        if ($activeWindow === null) {
            return null;
        }

        return $this->getStatus($activeWindow)['scheduledDelivery'];
    }

    /**
     * Compute [cutoffCarbon, deliveryCarbon] for the current week cycle.
     *
     * @return array{Carbon, Carbon}
     */
    public function computeWindow(BookingWindow $window, Carbon $now): array
    {
        $delivery = $now->copy()->startOfDay();

        // Advance to next occurrence of delivery_day (or stay if today is delivery day)
        if ($delivery->isoWeekday() !== $window->delivery_day) {
            $delivery->next(self::DAY_LABELS[$window->delivery_day]);
        }

        // Cutoff is on cutoff_day of the same ISO week as delivery, at cutoff_time
        $cutoff = $delivery->copy()->isoWeekday($window->cutoff_day);

        [$hour, $minute] = array_map('intval', explode(':', substr($window->cutoff_time, 0, 5)));
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
