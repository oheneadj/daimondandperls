<?php

use App\Enums\BookingStatus;
use App\Enums\PaymentGateway;
use App\Enums\PaymentGatewayStatus;
use App\Livewire\Admin\Payments\PaymentsOverview;
use App\Models\Booking;
use App\Models\Payment;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->user = User::factory()->create();
});

test('authenticated users can visit the payments overview', function () {
    $this->actingAs($this->user)
        ->get(route('admin.payments.index'))
        ->assertOk()
        ->assertSeeLivewire(PaymentsOverview::class);
});

test('it displays payment statistics', function () {
    Payment::factory()->create(['amount' => 100, 'status' => PaymentGatewayStatus::Successful]);
    Payment::factory()->create(['amount' => 50, 'status' => PaymentGatewayStatus::Successful]);
    Payment::factory()->create(['gateway' => PaymentGateway::Manual, 'status' => PaymentGatewayStatus::Pending]);
    Payment::factory()->create(['status' => PaymentGatewayStatus::Failed]);

    Livewire::actingAs($this->user)
        ->test(PaymentsOverview::class)
        ->assertSee('GH₵ 150.00') // Total Received
        ->assertSee('1'); // Pending & Failed counts
});

test('it can verify a manual payment and update booking status', function () {
    $booking = Booking::factory()->create(['status' => BookingStatus::Pending]);
    $payment = Payment::factory()->create([
        'booking_id' => $booking->id,
        'amount' => 100,
        'gateway' => PaymentGateway::Manual,
        'status' => PaymentGatewayStatus::Pending,
    ]);

    Livewire::actingAs($this->user)
        ->test(PaymentsOverview::class)
        ->call('confirmVerify', $payment->id)
        ->assertSet('showingVerifyModal', true)
        ->call('verifyPayment')
        ->assertSet('showingVerifyModal', false);

    expect($payment->fresh()->status)->toBe(PaymentGatewayStatus::Successful);
    expect($blocking = $booking->fresh())->status->toBe(BookingStatus::Confirmed);
    expect($blocking->payment_status->value)->toBe('paid');
});
