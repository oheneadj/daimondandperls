<?php

declare(strict_types=1);

use App\Enums\PaymentStatus;
use App\Models\Booking;
use App\Models\Payment;
use App\Services\Payment\Data\VerifyResult;
use App\Services\Payment\TransflowGateway;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;

uses(RefreshDatabase::class);

beforeEach(function () {
    Notification::fake();
});

// ── Success return — webhook already fired ────────────────────────────────────

it('redirects to confirmation when booking is already paid (webhook beat redirect)', function () {
    $booking = Booking::factory()->create([
        'payment_status' => PaymentStatus::Paid,
    ]);

    $response = $this->get(route('booking.payment.return', ['booking' => $booking->reference, 'status' => 'success']));

    $response->assertRedirectToRoute('booking.confirmation', ['booking' => $booking->reference]);
});

// ── Success return — verify confirms payment ──────────────────────────────────

it('marks booking paid and redirects to confirmation when verify confirms payment', function () {
    $booking = Booking::factory()->create([
        'payment_reference' => 'TXN-RETURN-001',
        'payment_status' => PaymentStatus::Pending,
        'total_amount' => 300,
    ]);

    $this->mock(TransflowGateway::class, function ($mock) {
        $mock->shouldReceive('verify')
            ->once()
            ->with('TXN-RETURN-001')
            ->andReturn(VerifyResult::confirmed(reference: 'TXN-RETURN-001', amount: 300.0, raw: []));
    });

    $response = $this->get(route('booking.payment.return', ['booking' => $booking->reference, 'status' => 'success']));

    $response->assertRedirectToRoute('booking.confirmation', ['booking' => $booking->reference]);
    expect($booking->fresh()->payment_status)->toBe(PaymentStatus::Paid);
    $this->assertDatabaseHas('payments', [
        'booking_id' => $booking->id,
        'gateway' => 'transflow',
        'status' => 'successful',
    ]);
});

// ── Success return — verify pending (webhook hasn't fired yet) ────────────────

it('redirects to payment page with payment_awaiting flag when verify is not yet confirmed', function () {
    $booking = Booking::factory()->create([
        'payment_reference' => 'TXN-RETURN-002',
        'payment_status' => PaymentStatus::Pending,
    ]);

    $this->mock(TransflowGateway::class, function ($mock) {
        $mock->shouldReceive('verify')
            ->once()
            ->with('TXN-RETURN-002')
            ->andReturn(VerifyResult::failed());
    });

    $response = $this->get(route('booking.payment.return', ['booking' => $booking->reference, 'status' => 'success']));

    $response->assertRedirectToRoute('booking.payment', ['booking' => $booking->reference]);
    $response->assertSessionHas('payment_awaiting', true);
});

// ── Failure return ────────────────────────────────────────────────────────────

it('marks booking failed and redirects to payment page on failure return', function () {
    $booking = Booking::factory()->create([
        'payment_status' => PaymentStatus::Pending,
    ]);

    $response = $this->get(route('booking.payment.return', ['booking' => $booking->reference, 'status' => 'failure']));

    $response->assertRedirectToRoute('booking.payment', ['booking' => $booking->reference]);
    $response->assertSessionHas('error');
    expect($booking->fresh()->payment_status)->toBe(PaymentStatus::Failed);
});

it('does not overwrite already-failed booking on duplicate failure return', function () {
    $booking = Booking::factory()->create([
        'payment_status' => PaymentStatus::Failed,
        'payment_details' => ['original' => true],
    ]);

    $this->get(route('booking.payment.return', ['booking' => $booking->reference, 'status' => 'failure']));

    // Should remain failed, payment_details unchanged
    expect($booking->fresh()->payment_status)->toBe(PaymentStatus::Failed);
});
