<?php

declare(strict_types=1);

use App\Enums\BookingStatus;
use App\Enums\PaymentStatus;
use App\Models\Booking;

it('cancels old unpaid bookings', function (): void {
    // 25h old, Pending/Unpaid — should be cancelled
    $abandoned = Booking::factory()->create([
        'status' => BookingStatus::Pending,
        'payment_status' => PaymentStatus::Unpaid,
        'created_at' => now()->subHours(25),
    ]);

    // 25h old, Pending/Pending (payment initiated) — should NOT be cancelled
    $paymentInitiated = Booking::factory()->create([
        'status' => BookingStatus::Pending,
        'payment_status' => PaymentStatus::Pending,
        'created_at' => now()->subHours(25),
    ]);

    // 1h old, Pending/Unpaid — should NOT be cancelled (too recent)
    $recent = Booking::factory()->create([
        'status' => BookingStatus::Pending,
        'payment_status' => PaymentStatus::Unpaid,
        'created_at' => now()->subHour(),
    ]);

    $this->artisan('booking:cleanup-abandoned')
        ->expectsOutputToContain('Cancelled 1 abandoned booking(s)')
        ->assertSuccessful();

    expect($abandoned->fresh())
        ->status->toBe(BookingStatus::Cancelled)
        ->cancelled_at->not->toBeNull()
        ->cancelled_reason->toContain('24 hours');

    expect($paymentInitiated->fresh()->status)->toBe(BookingStatus::Pending);
    expect($recent->fresh()->status)->toBe(BookingStatus::Pending);
});

it('respects custom hours option', function (): void {
    $booking = Booking::factory()->create([
        'status' => BookingStatus::Pending,
        'payment_status' => PaymentStatus::Unpaid,
        'created_at' => now()->subHours(3),
    ]);

    $this->artisan('booking:cleanup-abandoned --hours=2')
        ->expectsOutputToContain('Cancelled 1 abandoned booking(s)')
        ->assertSuccessful();

    expect($booking->fresh()->status)->toBe(BookingStatus::Cancelled);
});
