<?php

declare(strict_types=1);

use App\Enums\PaymentStatus;
use App\Models\Booking;
use App\Models\Payment;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;

uses(RefreshDatabase::class);

beforeEach(function () {
    Notification::fake();
});

// Build the flat payload Transflow sends to our webhook
function transflowWebhookPayload(string $paymentReference, string $responseCode, array $extra = []): array
{
    return array_merge([
        'refNo' => $paymentReference,
        'responseCode' => $responseCode,
        'responseMessage' => $responseCode === '01' ? 'Transaction processed successfully' : 'Transaction failed',
        'amount' => '250.00',
        'msisdn' => '233241234567',
        'network' => 'MTN',
    ], $extra);
}

// ── Success path ──────────────────────────────────────────────────────────────

it('marks booking paid and creates payment record when responseCode is 01', function () {
    $booking = Booking::factory()->create([
        'payment_reference' => 'TXN-WH-001',
        'payment_status' => PaymentStatus::Pending,
        'total_amount' => 250,
    ]);

    $response = $this->postJson('/webhooks/transflow', transflowWebhookPayload('TXN-WH-001', '01'));

    $response->assertOk()->assertJson(['status' => 'success']);

    expect($booking->fresh()->payment_status)->toBe(PaymentStatus::Paid);
    $this->assertDatabaseHas('payments', [
        'booking_id' => $booking->id,
        'gateway' => 'transflow',
        'status' => 'successful',
    ]);
});

it('resolves payment method as card when network is CARD', function () {
    $booking = Booking::factory()->create([
        'payment_reference' => 'TXN-WH-CARD',
        'payment_status' => PaymentStatus::Pending,
    ]);

    $this->postJson('/webhooks/transflow', transflowWebhookPayload('TXN-WH-CARD', '01', ['network' => 'CARD']));

    $this->assertDatabaseHas('payments', [
        'booking_id' => $booking->id,
        'method' => 'card',
    ]);
});

it('resolves payment method as mobile_money for MoMo networks', function () {
    $booking = Booking::factory()->create([
        'payment_reference' => 'TXN-WH-MOMO',
        'payment_status' => PaymentStatus::Pending,
    ]);

    $this->postJson('/webhooks/transflow', transflowWebhookPayload('TXN-WH-MOMO', '01', ['network' => 'MTN']));

    $this->assertDatabaseHas('payments', [
        'booking_id' => $booking->id,
        'method' => 'mobile_money',
    ]);
});

// ── Failure path ──────────────────────────────────────────────────────────────

it('marks booking failed when responseCode is not 01', function () {
    $booking = Booking::factory()->create([
        'payment_reference' => 'TXN-WH-002',
        'payment_status' => PaymentStatus::Pending,
    ]);

    $response = $this->postJson('/webhooks/transflow', transflowWebhookPayload('TXN-WH-002', '09'));

    $response->assertOk()->assertJson(['status' => 'success']);
    expect($booking->fresh()->payment_status)->toBe(PaymentStatus::Failed);
});

// ── Edge cases ────────────────────────────────────────────────────────────────

it('returns ignored when refNo is missing from payload', function () {
    $response = $this->postJson('/webhooks/transflow', ['responseCode' => '01']);

    $response->assertOk()->assertJson(['status' => 'ignored', 'message' => 'Missing reference']);
});

it('returns ignored when no booking matches the refNo', function () {
    $response = $this->postJson('/webhooks/transflow', transflowWebhookPayload('TXN-DOES-NOT-EXIST', '01'));

    $response->assertOk()->assertJson(['status' => 'ignored', 'message' => 'Booking not found']);
});

it('is idempotent — does not duplicate payment records on repeated success webhooks', function () {
    $booking = Booking::factory()->create([
        'payment_reference' => 'TXN-WH-003',
        'payment_status' => PaymentStatus::Unpaid,
    ]);

    $this->postJson('/webhooks/transflow', transflowWebhookPayload('TXN-WH-003', '01'));
    $this->postJson('/webhooks/transflow', transflowWebhookPayload('TXN-WH-003', '01'));

    expect(Payment::where('booking_id', $booking->id)->count())->toBe(1);
});

it('stores the full callback payload in payment_details', function () {
    $booking = Booking::factory()->create([
        'payment_reference' => 'TXN-WH-004',
        'payment_status' => PaymentStatus::Pending,
    ]);

    $this->postJson('/webhooks/transflow', transflowWebhookPayload('TXN-WH-004', '01'));

    $details = $booking->fresh()->payment_details;
    expect($details)->not->toBeNull()
        ->and($details['refNo'])->toBe('TXN-WH-004');
});
