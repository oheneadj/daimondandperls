<?php

declare(strict_types=1);

use App\Models\Category;
use App\Models\Package;
use App\Services\BookingWindowService;
use Carbon\Carbon;

$service = fn () => new BookingWindowService;

// ── getStatus when no window configured ──────────────────────

it('returns disabled status when category has no booking window', function () use ($service) {
    $category = Category::factory()->create();

    $status = $service()->getStatus($category);

    expect($status['enabled'])->toBeFalse()
        ->and($status['open'])->toBeTrue()
        ->and($status['cutoff'])->toBeNull()
        ->and($status['nextDelivery'])->toBeNull();
});

// ── computeWindow ──────────────────────────────────────────────

it('computes cutoff before delivery in the same week', function () use ($service) {
    // delivery_day=3 (Wed), cutoff_day=2 (Tue), cutoff_time=06:00
    $category = Category::factory()->withBookingWindow(deliveryDay: 3, cutoffDay: 2, cutoffTime: '06:00:00')->create();

    // Use a Monday 10:00 — delivery should be Wed, cutoff should be Tue 06:00
    $now = Carbon::parse('next Monday')->setTime(10, 0);
    [$cutoff, $delivery] = $service()->computeWindow($category, $now);

    expect($cutoff->isoWeekday())->toBe(2)
        ->and($cutoff->hour)->toBe(6)
        ->and($cutoff->minute)->toBe(0)
        ->and($delivery->isoWeekday())->toBe(3)
        ->and($cutoff->lt($delivery))->toBeTrue();
});

it('rolls cutoff back one week when same-week placement would put it after delivery', function () use ($service) {
    // delivery_day=2 (Tue), cutoff_day=4 (Thu) — Thu comes after Tue, so cutoff must be previous Thu
    $category = Category::factory()->withBookingWindow(deliveryDay: 2, cutoffDay: 4, cutoffTime: '09:00:00')->create();

    $now = Carbon::parse('next Monday')->setTime(10, 0);
    [$cutoff, $delivery] = $service()->computeWindow($category, $now);

    expect($cutoff->lt($delivery))->toBeTrue()
        ->and($cutoff->isoWeekday())->toBe(4);
});

it('advances both cutoff and delivery by one week when past delivery end of day', function () use ($service) {
    // delivery_day=3 (Wed), cutoff_day=2 (Tue)
    // Use a Thursday — past Wednesday's delivery
    $category = Category::factory()->withBookingWindow(deliveryDay: 3, cutoffDay: 2, cutoffTime: '06:00:00')->create();

    $now = Carbon::parse('next Thursday')->setTime(14, 0);
    [$cutoff, $delivery] = $service()->computeWindow($category, $now);

    // Delivery should be Wednesday of the following week
    expect($delivery->isoWeekday())->toBe(3)
        ->and($delivery->isAfter($now))->toBeTrue()
        ->and($cutoff->lt($delivery))->toBeTrue();
});

// ── getStatus open/closed ──────────────────────────────────────

it('reports window as open when now is before cutoff', function () use ($service) {
    // delivery Wednesday, cutoff Tuesday 23:59 — use Monday
    $category = Category::factory()->withBookingWindow(deliveryDay: 3, cutoffDay: 2, cutoffTime: '23:59:00')->create();

    Carbon::setTestNow(Carbon::parse('next Monday')->setTime(8, 0));

    $status = $service()->getStatus($category);

    expect($status['open'])->toBeTrue()
        ->and($status['enabled'])->toBeTrue();

    Carbon::setTestNow();
});

it('reports window as closed when now is after cutoff', function () use ($service) {
    // delivery Wednesday, cutoff Tuesday 06:00 — use Tuesday 12:00 (past cutoff)
    $category = Category::factory()->withBookingWindow(deliveryDay: 3, cutoffDay: 2, cutoffTime: '06:00:00')->create();

    Carbon::setTestNow(Carbon::parse('next Tuesday')->setTime(12, 0));

    $status = $service()->getStatus($category);

    expect($status['open'])->toBeFalse()
        ->and($status['scheduledDelivery'])->not->toBeNull();

    Carbon::setTestNow();
});

it('schedules delivery one week out when window is closed', function () use ($service) {
    $category = Category::factory()->withBookingWindow(deliveryDay: 3, cutoffDay: 2, cutoffTime: '06:00:00')->create();

    Carbon::setTestNow(Carbon::parse('next Tuesday')->setTime(12, 0));

    $status = $service()->getStatus($category);

    // scheduledDelivery should be next week's Wednesday (not this week's)
    expect($status['scheduledDelivery']->isoWeekday())->toBe(3)
        ->and($status['scheduledDelivery']->gt($status['nextDelivery']))->toBeTrue();

    Carbon::setTestNow();
});

// ── isOpenForPackage / getScheduledDeliveryForPackage ──────────

it('always returns open regardless of window state', function () use ($service) {
    Carbon::setTestNow(Carbon::parse('next Tuesday')->setTime(12, 0)); // past cutoff

    expect($service()->isOpenForPackage())->toBeTrue();

    Carbon::setTestNow();
});

it('returns null scheduled date for window_exempt package', function () use ($service) {
    $category = Category::factory()->withBookingWindow(deliveryDay: 3, cutoffDay: 2, cutoffTime: '06:00:00')->create();
    $package = Package::factory()->create(['category_id' => $category->id, 'window_exempt' => true]);

    expect($service()->getScheduledDeliveryForPackage($package))->toBeNull();
});

it('returns scheduled delivery date for windowed package past cutoff', function () use ($service) {
    $category = Category::factory()->withBookingWindow(deliveryDay: 3, cutoffDay: 2, cutoffTime: '06:00:00')->create();
    $package = Package::factory()->create(['category_id' => $category->id, 'window_exempt' => false]);

    Carbon::setTestNow(Carbon::parse('next Tuesday')->setTime(12, 0)); // past cutoff

    $date = $service()->getScheduledDeliveryForPackage($package);

    expect($date)->not->toBeNull()
        ->and($date->isoWeekday())->toBe(3); // Wednesday

    Carbon::setTestNow();
});
