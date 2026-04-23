<?php

declare(strict_types=1);

use App\Models\Booking;
use App\Services\Payment\Data\InitiateResult;
use App\Services\Payment\Data\VerifyResult;
use App\Services\Payment\MoolreGateway;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;

uses(RefreshDatabase::class);

beforeEach(function () {
    config([
        'payments.gateways.moolre.base_url' => 'https://api.moolre.test/open/transact',
        'payments.gateways.moolre.api_user' => 'test-user',
        'payments.gateways.moolre.pub_key' => 'test-pubkey',
        'payments.gateways.moolre.merchant_id' => 'test-merchant',
    ]);

    $this->gateway = new MoolreGateway;
    $this->booking = Booking::factory()->create(['total_amount' => 500, 'reference' => 'REF-001']);
});

it('returns prompt_sent when moolre responds with status 1', function () {
    Http::fake([
        '*/payment' => Http::response(['status' => 1, 'data' => 'TXN-ABC123', 'code' => 'TP00'], 200),
    ]);

    $result = $this->gateway->initiate($this->booking, ['channel' => '13', 'payer' => '0241234567']);

    expect($result)->toBeInstanceOf(InitiateResult::class)
        ->and($result->isPromptSent())->toBeTrue()
        ->and($result->reference)->toBe('TXN-ABC123');
});

it('returns otp_required when moolre responds with TP14', function () {
    Http::fake([
        '*/payment' => Http::response(['code' => 'TP14', 'message' => 'Enter OTP sent to your phone.'], 200),
    ]);

    $result = $this->gateway->initiate($this->booking, ['channel' => '13', 'payer' => '0241234567']);

    expect($result->requiresOtp())->toBeTrue()
        ->and($result->message)->toBe('Enter OTP sent to your phone.');
});

it('returns error when moolre responds with unexpected status', function () {
    Http::fake([
        '*/payment' => Http::response(['status' => 0, 'message' => 'Insufficient funds.'], 200),
    ]);

    $result = $this->gateway->initiate($this->booking, ['channel' => '13', 'payer' => '0241234567']);

    expect($result->failed())->toBeTrue()
        ->and($result->message)->toBe('Insufficient funds.');
});

it('returns error when moolre API is unreachable', function () {
    Http::fake([
        '*/payment' => fn () => throw new \Exception('Connection refused'),
    ]);

    $result = $this->gateway->initiate($this->booking, ['channel' => '13', 'payer' => '0241234567']);

    expect($result->failed())->toBeTrue();
});

it('submits otp and re-initiates when moolre returns TP17', function () {
    Http::fake([
        '*/payment' => Http::sequence()
            ->push(['code' => 'TP17'], 200)
            ->push(['status' => 1, 'data' => 'TXN-XYZ'], 200),
    ]);

    $result = $this->gateway->submitOtp($this->booking, '13', '0241234567', '123456');

    expect($result->isPromptSent())->toBeTrue()
        ->and($result->reference)->toBe('TXN-XYZ');
});

it('returns error when moolre returns TP15 (wrong OTP)', function () {
    Http::fake([
        '*/payment' => Http::response(['code' => 'TP15', 'message' => 'Invalid OTP.'], 200),
    ]);

    $result = $this->gateway->submitOtp($this->booking, '13', '0241234567', '000000');

    expect($result->failed())->toBeTrue();
});

it('returns confirmed verify result when txstatus is 1', function () {
    Http::fake([
        '*/status' => Http::response(['data' => ['txstatus' => 1, 'amount' => 500.0]], 200),
    ]);

    $result = $this->gateway->verify('REF-001');

    expect($result)->toBeInstanceOf(VerifyResult::class)
        ->and($result->paid)->toBeTrue()
        ->and($result->amount)->toBe(500.0);
});

it('returns failed verify result when txstatus is not 1', function () {
    Http::fake([
        '*/status' => Http::response(['data' => ['txstatus' => 2]], 200),
    ]);

    $result = $this->gateway->verify('REF-001');

    expect($result->paid)->toBeFalse();
});
