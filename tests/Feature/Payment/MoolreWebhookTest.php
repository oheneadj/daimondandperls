<?php

declare(strict_types=1);

use App\Enums\PaymentStatus;
use App\Models\Booking;
use App\Models\Payment;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;

uses(RefreshDatabase::class);

beforeEach(function () {
    config(['payments.gateways.moolre.webhook_secret' => '']);
    Notification::fake();
});

function webhookPayload(string $reference, int $txstatus): array
{
    return [
        'data' => [
            'externalref' => $reference,
            'txstatus' => $txstatus,
        ],
    ];
}

it('marks booking as paid and creates payment record on txstatus 1', function () {
    $booking = Booking::factory()->create([
        'reference' => 'REF-WEBHOOK-001',
        'payment_status' => PaymentStatus::Unpaid,
        'total_amount' => 300,
    ]);

    $response = $this->postJson('/webhooks/moolre', webhookPayload('REF-WEBHOOK-001', 1));

    $response->assertOk()->assertJson(['status' => 'success']);

    expect($booking->fresh()->payment_status)->toBe(PaymentStatus::Paid);
    $this->assertDatabaseHas('payments', [
        'booking_id' => $booking->id,
        'gateway' => 'moolre',
        'status' => 'successful',
    ]);
});

it('marks booking as failed on txstatus 2', function () {
    $booking = Booking::factory()->create([
        'reference' => 'REF-WEBHOOK-002',
        'payment_status' => PaymentStatus::Pending,
    ]);

    $response = $this->postJson('/webhooks/moolre', webhookPayload('REF-WEBHOOK-002', 2));

    $response->assertOk()->assertJson(['status' => 'success']);
    expect($booking->fresh()->payment_status)->toBe(PaymentStatus::Failed);
});

it('returns ignored when booking is not found', function () {
    $response = $this->postJson('/webhooks/moolre', webhookPayload('MISSING-REF', 1));

    $response->assertOk()->assertJson(['status' => 'ignored']);
});

it('returns ignored when webhook secret does not match', function () {
    config(['payments.gateways.moolre.webhook_secret' => 'correct-secret']);

    $payload = webhookPayload('REF-X', 1);
    $payload['data']['secret'] = 'wrong-secret';

    $response = $this->postJson('/webhooks/moolre', $payload);

    $response->assertOk()->assertJson(['status' => 'ignored', 'message' => 'Invalid signature']);
});

it('accepts when webhook secret matches', function () {
    config(['payments.gateways.moolre.webhook_secret' => 'correct-secret']);

    $booking = Booking::factory()->create([
        'reference' => 'REF-WEBHOOK-003',
        'payment_status' => PaymentStatus::Unpaid,
    ]);

    $payload = webhookPayload('REF-WEBHOOK-003', 1);
    $payload['data']['secret'] = 'correct-secret';

    $response = $this->postJson('/webhooks/moolre', $payload);

    $response->assertOk()->assertJson(['status' => 'success']);
    expect($booking->fresh()->payment_status)->toBe(PaymentStatus::Paid);
});

it('is idempotent — does not duplicate payment records on repeated success webhooks', function () {
    $booking = Booking::factory()->create([
        'reference' => 'REF-WEBHOOK-004',
        'payment_status' => PaymentStatus::Unpaid,
    ]);

    $this->postJson('/webhooks/moolre', webhookPayload('REF-WEBHOOK-004', 1));
    $this->postJson('/webhooks/moolre', webhookPayload('REF-WEBHOOK-004', 1));

    expect(Payment::where('booking_id', $booking->id)->count())->toBe(1);
});
