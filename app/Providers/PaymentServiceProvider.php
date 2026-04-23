<?php

declare(strict_types=1);

namespace App\Providers;

use App\Contracts\PaymentGatewayContract;
use App\Services\Payment\PaymentManager;
use Illuminate\Support\ServiceProvider;

/*
|--------------------------------------------------------------------------
| Payment Service Provider
|--------------------------------------------------------------------------
|
| Registers the payment system into Laravel's service container.
|
| After this, you can resolve the active gateway anywhere like this:
|
|   app(PaymentGatewayContract::class)->initiate($booking, $context);
|   app(PaymentManager::class)->driver('moolre')->initiate($booking, $context);
|
| The PaymentManager is registered as a singleton so it's only built once
| per request — no repeated database reads for the active gateway setting.
|
*/

class PaymentServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        // Register the manager as a singleton
        $this->app->singleton(PaymentManager::class, function ($app) {
            return new PaymentManager($app);
        });

        // Bind the contract to whichever driver is currently active.
        // This is what makes app(PaymentGatewayContract::class) work
        // without the calling code knowing which gateway is running.
        $this->app->bind(PaymentGatewayContract::class, function ($app) {
            return $app->make(PaymentManager::class)->driver();
        });
    }
}
