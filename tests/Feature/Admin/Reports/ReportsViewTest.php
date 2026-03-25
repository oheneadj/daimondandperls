<?php

use App\Enums\BookingStatus;
use App\Enums\PaymentGatewayStatus;
use App\Livewire\Admin\Reports\ReportsView;
use App\Models\Booking;
use App\Models\Payment;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->user = User::factory()->create();
});

test('authenticated users can visit the reports view', function () {
    $this->actingAs($this->user)
        ->get(route('admin.reports.index'))
        ->assertOk()
        ->assertSeeLivewire(ReportsView::class);
});

test('it displays report statistics for the selected period', function () {
    // Create a booking in the current month
    $booking = Booking::factory()->create(['created_at' => now(), 'status' => BookingStatus::Completed]);
    Payment::factory()->create([
        'booking_id' => $booking->id,
        'amount' => 500,
        'status' => PaymentGatewayStatus::Successful,
        'paid_at' => now(),
    ]);

    Livewire::actingAs($this->user)
        ->test(ReportsView::class)
        ->assertSee('GH₵ 500.00') // Total Revenue
        ->assertSee('1') // Total Bookings
        ->assertSee('GH₵ 500.00'); // Avg Booking Value
});

test('it filters statistics by period', function () {
    // Booking from last month
    Booking::factory()->create(['created_at' => now()->subMonth(), 'status' => BookingStatus::Completed]);

    Livewire::actingAs($this->user)
        ->test(ReportsView::class)
        ->set('period', 'today')
        ->assertSee('0'); // Total Bookings for today should be 0 if no bookings today
});

test('it lists upcoming events', function () {
    Booking::factory()->create([
        'event_date' => now()->addDays(2),
        'status' => BookingStatus::Confirmed,
    ]);

    Livewire::actingAs($this->user)
        ->test(ReportsView::class)
        ->assertSee(now()->addDays(2)->format('d M, Y'));
});
