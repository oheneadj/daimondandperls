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

    // 1. Total bookings today (3)
    Booking::factory()->count(3)->create([
        'created_at' => today(),
        'event_date' => now()->subMonths(2), // Exclude from upcoming events
        'payment_status' => PaymentStatus::Paid, // Exclude from pending payments
        'status' => BookingStatus::Cancelled, // Exclude from completed bookings
    ]);
    Booking::factory()->count(2)->create([ // (Not counted)
        'created_at' => now()->subDay(),
        'event_date' => now()->subMonths(2),
        'payment_status' => PaymentStatus::Paid,
        'status' => BookingStatus::Cancelled,
    ]);

    // 2. Upcoming events (4)
    Booking::factory()->count(4)->create([
        'event_date' => now()->addDays(2),
        'created_at' => now()->subDays(2), // Exclude from today's bookings
        'payment_status' => PaymentStatus::Paid,
        'status' => BookingStatus::Cancelled,
    ]);
    Booking::factory()->count(1)->create([ // (Not counted)
        'event_date' => now()->subDays(2),
        'created_at' => now()->subDays(2),
        'payment_status' => PaymentStatus::Paid,
        'status' => BookingStatus::Cancelled,
    ]);

    // 3. Pending payments (3)
    Booking::factory()->count(2)->create([
        'payment_status' => PaymentStatus::Pending,
        'created_at' => now()->subDays(2),
        'event_date' => now()->subMonths(2),
        'status' => BookingStatus::Cancelled,
    ]);
    Booking::factory()->count(1)->create([
        'payment_status' => PaymentStatus::Unpaid,
        'created_at' => now()->subDays(2),
        'event_date' => now()->subMonths(2),
        'status' => BookingStatus::Cancelled,
    ]);
    Booking::factory()->count(5)->create([ // (Not counted)
        'payment_status' => PaymentStatus::Paid,
        'created_at' => now()->subDays(2),
        'event_date' => now()->subMonths(2),
        'status' => BookingStatus::Cancelled,
    ]);

    // 4. Completed bookings (4)
    Booking::factory()->count(4)->create([
        'status' => BookingStatus::Completed,
        'created_at' => now()->subDays(2),
        'event_date' => now()->subMonths(2),
        'payment_status' => PaymentStatus::Paid,
    ]);
    Booking::factory()->count(3)->create([ // (Not counted)
        'status' => BookingStatus::Pending,
        'created_at' => now()->subDays(2),
        'event_date' => now()->subMonths(2),
        'payment_status' => PaymentStatus::Paid,
    ]);

    Livewire::test(Dashboard::class)
        ->assertSet('totalBookingsToday', 3)
        ->assertSet('upcomingEvents', 4)
        ->assertSet('pendingPayments', 3)
        ->assertSet('completedBookings', 4);
});
