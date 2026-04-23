<?php

declare(strict_types=1);

use App\Contracts\PaymentGatewayContract;
use App\Models\Setting;
use App\Services\Payment\MoolreGateway;
use App\Services\Payment\PaymentManager;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('resolves moolre as default driver from config', function () {
    config(['payments.default' => 'moolre']);

    $driver = app(PaymentManager::class)->driver();

    expect($driver)->toBeInstanceOf(MoolreGateway::class);
});

it('resolves the active gateway from the settings table', function () {
    Setting::create(['key' => 'active_payment_gateway', 'value' => 'moolre']);

    $driver = app(PaymentManager::class)->driver();

    expect($driver)->toBeInstanceOf(MoolreGateway::class);
});

it('falls back to config default when setting is missing', function () {
    config(['payments.default' => 'moolre']);

    // No setting row exists — should fall back
    $driver = app(PaymentManager::class)->driver();

    expect($driver)->toBeInstanceOf(MoolreGateway::class);
});

it('resolves PaymentGatewayContract binding to the active driver', function () {
    config(['payments.default' => 'moolre']);

    $gateway = app(PaymentGatewayContract::class);

    expect($gateway)->toBeInstanceOf(MoolreGateway::class)
        ->and($gateway)->toBeInstanceOf(PaymentGatewayContract::class);
});
