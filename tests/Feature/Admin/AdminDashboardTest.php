<?php

use App\Enums\PaymentGatewayStatus;
use App\Enums\PaymentStatus;
use App\Livewire\Dashboard;
use App\Models\Booking;
use App\Models\Package;
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

test('it displays dashboard statistics', function () {
    // Current month booking
    Booking::factory()->create(['created_at' => now()]);

    // Revenue from current month
    $booking = Booking::factory()->create();
    Payment::factory()->create([
        'booking_id' => $booking->id,
        'amount' => 1250.50,
        'status' => PaymentGatewayStatus::Successful,
        'paid_at' => now(),
    ]);

    // Active package
    Package::factory()->create(['is_active' => true]);

    // Unpaid booking
    Booking::factory()->create(['payment_status' => PaymentStatus::Unpaid]);

    Livewire::actingAs($this->user)
        ->test(Dashboard::class)
        ->assertSee('2') // Total Bookings (1 + 1 from revenue setup)
        ->assertSee('GH₵1,250.50') // Revenue
        ->assertSee('1'); // Active items & Unpaid slips
});

test('it lists recent bookings', function () {
    $booking = Booking::factory()->create(['reference' => 'DB-TEST-001']);

    Livewire::actingAs($this->user)
        ->test(Dashboard::class)
        ->assertSee('DB-TEST-001')
        ->assertSee($booking->customer->name);
});
