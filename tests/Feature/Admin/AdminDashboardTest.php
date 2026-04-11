<?php

use App\Enums\PaymentGatewayStatus;
use App\Enums\PaymentStatus;
use App\Livewire\Dashboard;
use App\Models\Booking;
use App\Models\Payment;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->user = User::factory()->create();
});

test('authenticated users can visit the admin dashboard', function () {
    $this->actingAs($this->user)
        ->get(route('admin.dashboard'))
        ->assertOk()
        ->assertSeeLivewire(Dashboard::class);
});

test('it displays monthly revenue from successful payments', function () {
    $booking = Booking::factory()->create([
        'payment_status' => PaymentStatus::Paid,
        'status' => \App\Enums\BookingStatus::Completed,
    ]);
    Payment::factory()->create([
        'booking_id' => $booking->id,
        'amount' => 1250.50,
        'status' => PaymentGatewayStatus::Successful,
        'paid_at' => now(),
    ]);

    Livewire::actingAs($this->user)
        ->test(Dashboard::class)
        ->assertSet('revenueMonth', 1250.50);
});

test('it counts unpaid and pending bookings as needing attention', function () {
    // These should be counted
    Booking::factory()->create(['payment_status' => PaymentStatus::Unpaid, 'status' => \App\Enums\BookingStatus::Pending]);
    Booking::factory()->create(['payment_status' => PaymentStatus::Pending, 'status' => \App\Enums\BookingStatus::Confirmed]);

    // These should NOT be counted
    Booking::factory()->create(['payment_status' => PaymentStatus::Paid, 'status' => \App\Enums\BookingStatus::Completed]);
    Booking::factory()->create(['payment_status' => PaymentStatus::Unpaid, 'status' => \App\Enums\BookingStatus::Cancelled]);

    Livewire::actingAs($this->user)
        ->test(Dashboard::class)
        ->assertSet('needsAttentionCount', 2);
});

test('it lists recent bookings', function () {
    $booking = Booking::factory()->create(['reference' => 'DB-TEST-001']);

    Livewire::actingAs($this->user)
        ->test(Dashboard::class)
        ->assertSee('DB-TEST-001')
        ->assertSee($booking->customer->name);
});
