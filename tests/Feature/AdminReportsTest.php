<?php

declare(strict_types=1);

use App\Enums\PaymentGatewayStatus;
use App\Livewire\Admin\Reports\ReportsView;
use App\Models\Booking;
use App\Models\Customer;
use App\Models\Payment;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->admin = User::factory()->create();
});

it('allows admin to view reports page', function () {
    $this->actingAs($this->admin)
        ->get(route('admin.reports.index'))
        ->assertOk()
        ->assertSeeLivewire(ReportsView::class)
        ->assertSee('Reports & Analytics');
});

it('requires authentication to view reports', function () {
    $this->get(route('admin.reports.index'))
        ->assertRedirect(route('login'));
});

it('calculates total revenue correctly', function () {
    $customer = Customer::factory()->create();
    $booking = Booking::factory()->create(['customer_id' => $customer->id]);

    Payment::factory()->create([
        'booking_id' => $booking->id,
        'amount' => 150.00,
        'status' => PaymentGatewayStatus::Successful,
        'paid_at' => now(),
    ]);

    $booking2 = Booking::factory()->create(['customer_id' => $customer->id]);
    Payment::factory()->create([
        'booking_id' => $booking2->id,
        'amount' => 100.00,
        'status' => PaymentGatewayStatus::Failed, // Should not be counted
        'paid_at' => now(),
    ]);

    $this->actingAs($this->admin);

    Livewire::test(ReportsView::class)
        ->assertSee('150.00');
});

it('calculates daily booking count correctly', function () {
    Booking::factory()->count(3)->create(['created_at' => now()]);
    Booking::factory()->count(2)->create(['created_at' => now()->subDay()]);

    $this->actingAs($this->admin);

    Livewire::test(ReportsView::class)
        ->assertSet('dailyBookings', function ($dailyBookings) {
            return ($dailyBookings[now()->format('Y-m-d')] ?? null) === 3
                && ($dailyBookings[now()->subDay()->format('Y-m-d')] ?? null) === 2;
        });
});

it('exports csv response', function () {
    $this->actingAs($this->admin);

    Livewire::test(ReportsView::class)
        ->call('exportCsv')
        ->assertStatus(200);
});
