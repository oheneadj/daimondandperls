<?php

namespace Tests\Feature;

use App\Enums\BookingStatus;
use App\Enums\PaymentStatus;
use App\Livewire\Dashboard;
use App\Models\Booking;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;

uses(RefreshDatabase::class);

test('guests are redirected to the login page', function () {
    $response = $this->get(route('admin.dashboard'));
    $response->assertRedirect(route('login'));
});

test('authenticated users can visit the dashboard', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    Livewire::test(Dashboard::class)
        ->assertOk();
});

test('dashboard displays correct operational metrics', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    // Today's bookings (3)
    Booking::factory()->count(3)->create([
        'created_at' => today(),
        'payment_status' => PaymentStatus::Paid,
        'status' => BookingStatus::Completed,
    ]);

    // Yesterday's bookings — not counted in today
    Booking::factory()->count(2)->create([
        'created_at' => now()->subDay(),
        'payment_status' => PaymentStatus::Paid,
        'status' => BookingStatus::Completed,
    ]);

    // Needs attention: unpaid (2) + pending (1), not cancelled
    Booking::factory()->count(2)->create([
        'created_at' => now()->subDays(3),
        'payment_status' => PaymentStatus::Unpaid,
        'status' => BookingStatus::Pending,
    ]);
    Booking::factory()->count(1)->create([
        'created_at' => now()->subDays(3),
        'payment_status' => PaymentStatus::Pending,
        'status' => BookingStatus::Confirmed,
    ]);
    // Cancelled unpaid — excluded from needsAttentionCount
    Booking::factory()->count(2)->create([
        'created_at' => now()->subDays(3),
        'payment_status' => PaymentStatus::Unpaid,
        'status' => BookingStatus::Cancelled,
    ]);

    Livewire::test(Dashboard::class)
        ->assertSet('totalBookingsToday', 3)
        ->assertSet('needsAttentionCount', 3);
});
