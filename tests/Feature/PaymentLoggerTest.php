<?php

declare(strict_types=1);

use App\Enums\PaymentStatus;
use App\Models\Booking;
use App\Models\PaymentLog;
use App\Services\Payment\PaymentLogger;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('writes a payment log entry with all provided fields', function () {
    PaymentLogger::log(
        event: 'initiate',
        gateway: 'transflow',
        direction: 'outbound',
        bookingReference: 'CAT-2026-00001',
        level: 'info',
        status: 'pending',
        gatewayRef: 'TXF-REF-001',
        network: 'MTN',
        payerNumber: '0244123456',
        rawRequest: ['amount' => 100.00],
        rawResponse: ['responseCode' => 200],
        httpStatus: 200,
        durationMs: 350,
    );

    expect(PaymentLog::count())->toBe(1);

    $log = PaymentLog::first();

    expect($log->event)->toBe('initiate')
        ->and($log->gateway)->toBe('transflow')
        ->and($log->direction)->toBe('outbound')
        ->and($log->booking_reference)->toBe('CAT-2026-00001')
        ->and($log->level)->toBe('info')
        ->and($log->status)->toBe('pending')
        ->and($log->gateway_ref)->toBe('TXF-REF-001')
        ->and($log->network)->toBe('MTN')
        ->and($log->payer_number)->toBe('0244123456')
        ->and($log->raw_request['amount'])->toEqual(100.00)
        ->and($log->raw_response)->toBe(['responseCode' => 200])
        ->and($log->http_status)->toBe(200)
        ->and($log->duration_ms)->toBe(350);
});

it('writes a payment log entry with only required fields', function () {
    PaymentLogger::log(
        event: 'http-error',
        gateway: 'moolre',
        direction: 'outbound',
        level: 'error',
        status: 'failed',
        errorMessage: 'Connection refused',
    );

    expect(PaymentLog::count())->toBe(1);

    $log = PaymentLog::first();

    expect($log->event)->toBe('http-error')
        ->and($log->gateway)->toBe('moolre')
        ->and($log->direction)->toBe('outbound')
        ->and($log->level)->toBe('error')
        ->and($log->status)->toBe('failed')
        ->and($log->error_message)->toBe('Connection refused')
        ->and($log->booking_reference)->toBeNull()
        ->and($log->raw_request)->toBeNull()
        ->and($log->raw_response)->toBeNull();
});

it('records transflow webhook inbound event in payment_logs', function () {
    $booking = Booking::factory()->create([
        'payment_reference' => 'TXF-REF-HOOK',
        'payment_status' => PaymentStatus::Pending,
    ]);

    $payload = [
        'refNo' => 'TXF-REF-HOOK',
        'responseCode' => '00',
        'responseMessage' => 'Payment failed',
        'amount' => '150.00',
    ];

    $this->withoutMiddleware(\Illuminate\Foundation\Http\Middleware\VerifyCsrfToken::class)
        ->postJson(route('webhooks.transflow'), $payload);

    $log = PaymentLog::where('event', 'webhook')->where('gateway', 'transflow')->first();

    expect($log)->not->toBeNull()
        ->and($log->direction)->toBe('inbound')
        ->and($log->booking_reference)->toBe('TXF-REF-HOOK')
        ->and($log->raw_response)->not->toBeNull();
});

it('records transflow webhook-paid event in payment_logs', function () {
    $booking = Booking::factory()->create([
        'payment_reference' => 'TXF-REF-PAID',
        'payment_status' => PaymentStatus::Pending,
    ]);

    $payload = [
        'refNo' => 'TXF-REF-PAID',
        'responseCode' => '01',
        'amount' => '200.00',
        'network' => 'MTN',
    ];

    $this->withoutMiddleware(\Illuminate\Foundation\Http\Middleware\VerifyCsrfToken::class)
        ->postJson(route('webhooks.transflow'), $payload);

    $paidLog = PaymentLog::where('event', 'webhook-paid')->where('gateway', 'transflow')->first();

    expect($paidLog)->not->toBeNull()
        ->and($paidLog->status)->toBe('paid')
        ->and($paidLog->direction)->toBe('inbound')
        ->and($paidLog->booking_reference)->toBe($booking->reference);
});

it('records moolre webhook inbound event in payment_logs', function () {
    $booking = Booking::factory()->create([
        'payment_status' => PaymentStatus::Pending,
    ]);

    $payload = [
        'data' => [
            'externalref' => $booking->reference,
            'txstatus' => 2,
            'secret' => '',
        ],
    ];

    $this->withoutMiddleware(\Illuminate\Foundation\Http\Middleware\VerifyCsrfToken::class)
        ->postJson(route('webhooks.moolre'), $payload);

    $log = PaymentLog::where('event', 'webhook')->where('gateway', 'moolre')->first();

    expect($log)->not->toBeNull()
        ->and($log->direction)->toBe('inbound')
        ->and($log->raw_response)->not->toBeNull();
});
