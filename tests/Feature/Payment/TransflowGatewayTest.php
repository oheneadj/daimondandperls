<?php

declare(strict_types=1);

use App\Models\Booking;
use App\Services\Payment\Data\InitiateResult;
use App\Services\Payment\Data\VerifyResult;
use App\Services\Payment\TransflowGateway;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;

uses(RefreshDatabase::class);

beforeEach(function () {
    config([
        'payments.gateways.transflow.base_url' => 'https://transflow.test/checkout',
        'payments.gateways.transflow.api_key' => 'test-api-key',
        'payments.gateways.transflow.transflow_id' => 'test-tf-id',
        'payments.gateways.transflow.merchant_product_id' => 'test-product-id',
    ]);

    $this->gateway = new TransflowGateway;
    $this->booking = Booking::factory()->create(['total_amount' => 250, 'reference' => 'TF-001']);
});

// ── initiate() ───────────────────────────────────────────────────────────────

it('returns redirect result with checkout URL on success', function () {
    Http::fake([
        '*/request-payments' => Http::response([
            'responseCode' => 200,
            'data' => [
                'transactionReference' => 'TXN-TRANSFLOW-123',
                'checkoutUrl' => 'https://checkout.transflow.test/pay/TXN-TRANSFLOW-123',
            ],
        ], 200),
    ]);

    $result = $this->gateway->initiate($this->booking);

    expect($result)->toBeInstanceOf(InitiateResult::class)
        ->and($result->isRedirect())->toBeTrue()
        ->and($result->reference)->toBe('TXN-TRANSFLOW-123')
        ->and($result->redirectUrl)->toBe('https://checkout.transflow.test/pay/TXN-TRANSFLOW-123');
});

it('returns error when Transflow returns a non-200 responseCode', function () {
    Http::fake([
        '*/request-payments' => Http::response([
            'responseCode' => 400,
            'responseMessage' => 'Invalid merchant credentials.',
        ], 200),
    ]);

    $result = $this->gateway->initiate($this->booking);

    expect($result->failed())->toBeTrue()
        ->and($result->message)->toBe('Invalid merchant credentials.');
});

it('returns error when the Transflow API is unreachable', function () {
    Http::fake([
        '*/request-payments' => fn () => throw new \Exception('Connection refused'),
    ]);

    $result = $this->gateway->initiate($this->booking);

    expect($result->failed())->toBeTrue();
});

// ── verify() ─────────────────────────────────────────────────────────────────

it('returns confirmed verify result when responseCode is 01', function () {
    Http::fake([
        '*/check-transaction-status' => Http::response([
            'data' => [
                'responseCode' => '01',
                'amount' => '250.00',
            ],
        ], 200),
    ]);

    $result = $this->gateway->verify('TXN-TRANSFLOW-123');

    expect($result)->toBeInstanceOf(VerifyResult::class)
        ->and($result->paid)->toBeTrue()
        ->and($result->amount)->toBe(250.0);
});

it('returns failed verify result when responseCode is not 01', function () {
    Http::fake([
        '*/check-transaction-status' => Http::response([
            'data' => [
                'responseCode' => '09',
            ],
        ], 200),
    ]);

    $result = $this->gateway->verify('TXN-TRANSFLOW-123');

    expect($result->paid)->toBeFalse();
});

it('returns failed verify result when API is unreachable', function () {
    Http::fake([
        '*/check-transaction-status' => fn () => throw new \Exception('Timeout'),
    ]);

    $result = $this->gateway->verify('TXN-TRANSFLOW-123');

    expect($result->paid)->toBeFalse();
});
