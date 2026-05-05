<?php

declare(strict_types=1);

use App\Models\BookingWindow;
use App\Models\Package;
use App\Services\BookingWindowService;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

$service = fn () => new BookingWindowService;

// ── computeWindow ──────────────────────────────────────────────

it('computes cutoff before delivery in the same week', function () use ($service) {
    // delivery_day=3 (Wed), cutoff_day=2 (Tue), cutoff_time=06:00
    $window = BookingWindow::factory()->create([
        'delivery_day' => 3,
        'cutoff_day' => 2,
        'cutoff_time' => '06:00:00',
    ]);

    $now = Carbon::parse('next Monday')->setTime(10, 0);
    [$cutoff, $delivery] = $service()->computeWindow($window, $now);

    expect($cutoff->isoWeekday())->toBe(2)
        ->and($cutoff->hour)->toBe(6)
        ->and($cutoff->minute)->toBe(0)
        ->and($delivery->isoWeekday())->toBe(3)
        ->and($cutoff->lt($delivery))->toBeTrue();
});

it('rolls cutoff back one week when same-week placement would put it after delivery', function () use ($service) {
    // delivery_day=2 (Tue), cutoff_day=4 (Thu) — Thu comes after Tue, so cutoff must be previous Thu
    $window = BookingWindow::factory()->create([
        'delivery_day' => 2,
        'cutoff_day' => 4,
        'cutoff_time' => '09:00:00',
    ]);

    $now = Carbon::parse('next Monday')->setTime(10, 0);
    [$cutoff, $delivery] = $service()->computeWindow($window, $now);

    expect($cutoff->lt($delivery))->toBeTrue()
        ->and($cutoff->isoWeekday())->toBe(4);
});

it('advances both cutoff and delivery by one week when past delivery end of day', function () use ($service) {
    // delivery_day=3 (Wed), cutoff_day=2 (Tue)
    // Use a Thursday — past Wednesday's delivery
    $window = BookingWindow::factory()->create([
        'delivery_day' => 3,
        'cutoff_day' => 2,
        'cutoff_time' => '06:00:00',
    ]);

    $now = Carbon::parse('next Thursday')->setTime(14, 0);
    [$cutoff, $delivery] = $service()->computeWindow($window, $now);

    expect($delivery->isoWeekday())->toBe(3)
        ->and($delivery->isAfter($now))->toBeTrue()
        ->and($cutoff->lt($delivery))->toBeTrue();
});

// ── getStatus open/closed ──────────────────────────────────────

it('reports window as open when now is before cutoff', function () use ($service) {
    $window = BookingWindow::factory()->create([
        'delivery_day' => 3,
        'cutoff_day' => 2,
        'cutoff_time' => '23:59:00',
    ]);

    Carbon::setTestNow(Carbon::parse('next Monday')->setTime(8, 0));

    $status = $service()->getStatus($window);

    expect($status['open'])->toBeTrue();

    Carbon::setTestNow();
});

it('reports window as closed when now is after cutoff', function () use ($service) {
    $window = BookingWindow::factory()->create([
        'delivery_day' => 3,
        'cutoff_day' => 2,
        'cutoff_time' => '06:00:00',
    ]);

    Carbon::setTestNow(Carbon::parse('next Tuesday')->setTime(12, 0));

    $status = $service()->getStatus($window);

    expect($status['open'])->toBeFalse()
        ->and($status['scheduledDelivery'])->not->toBeNull();

    Carbon::setTestNow();
});

it('schedules delivery one week out when window is closed', function () use ($service) {
    $window = BookingWindow::factory()->create([
        'delivery_day' => 3,
        'cutoff_day' => 2,
        'cutoff_time' => '06:00:00',
    ]);

    Carbon::setTestNow(Carbon::parse('next Tuesday')->setTime(12, 0));

    $status = $service()->getStatus($window);

    expect($status['scheduledDelivery']->isoWeekday())->toBe(3)
        ->and($status['scheduledDelivery']->gt($status['nextDelivery']))->toBeTrue();

    Carbon::setTestNow();
});

it('getActiveWindow returns null for a package with no windows', function () use ($service) {
    $package = Package::factory()->create();

    expect($service()->getActiveWindow($package))->toBeNull();
});
