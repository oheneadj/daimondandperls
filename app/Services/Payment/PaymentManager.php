<?php

declare(strict_types=1);

namespace App\Services\Payment;

use App\Contracts\PaymentGatewayContract;
use App\Models\Setting;
use Illuminate\Support\Manager;

/*
|--------------------------------------------------------------------------
| Payment Manager
|--------------------------------------------------------------------------
|
| Extends Laravel's built-in Manager class — the same pattern Laravel
| uses internally for Cache, Mail, Auth, Queue, etc.
|
| Usage anywhere in the app:
|
|   // Resolves whichever gateway is active in the settings table
|   app(PaymentGatewayContract::class)->initiate($booking, $context);
|
|   // Force a specific driver (useful in tests)
|   app(PaymentManager::class)->driver('moolre')->initiate($booking, $context);
|
| To add a new gateway later:
|   1. Create the gateway class implementing PaymentGatewayContract
|   2. Add a createYourGatewayDriver() method below (same pattern as Moolre)
|   3. That's it — the manager handles the rest
|
*/

class PaymentManager extends Manager
{
    /**
     * Which gateway to use when none is explicitly requested.
     * Reads from the settings table first, then falls back to config.
     */
    public function getDefaultDriver(): string
    {
        // The active gateway is set in the admin settings panel at runtime.
        // We fall back to the config default when the setting doesn't exist.
        $fromSettings = Setting::where('key', 'active_payment_gateway')->value('value');

        return $fromSettings ?: config('payments.default', 'moolre');
    }

    /**
     * Build the Moolre gateway driver.
     * Laravel calls this automatically when driver('moolre') is requested.
     */
    protected function createMoolreDriver(): PaymentGatewayContract
    {
        return new MoolreGateway;
    }

    /**
     * Build the Transflow gateway driver.
     * Redirect-based: initiate() returns a hosted checkout URL.
     */
    protected function createTransflowDriver(): PaymentGatewayContract
    {
        return new TransflowGateway;
    }
}
