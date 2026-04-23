<?php

declare(strict_types=1);

/*
|--------------------------------------------------------------------------
| Payment Gateways Configuration
|--------------------------------------------------------------------------
|
| Each gateway has its own block here. Credentials come from .env — they
| should never be stored in the database.
|
| The active gateway is controlled at runtime via the settings table:
|   Setting::get('active_payment_gateway') → 'moolre', 'paystack', etc.
|
| To add a new gateway later:
|   1. Add its credentials block here
|   2. Create App\Services\Payment\YourGateway (implements PaymentGatewayContract)
|   3. Add createYourGatewayDriver() to PaymentManager
|   4. Create its webhook controller
|   5. Register the webhook route
|
*/

return [

    /*
    |--------------------------------------------------------------------------
    | Default / Fallback Gateway
    |--------------------------------------------------------------------------
    |
    | Used when the settings table doesn't have an active gateway set.
    |
    */
    'default' => env('PAYMENT_GATEWAY', 'transflow'),

    /*
    |--------------------------------------------------------------------------
    | Gateway Credentials
    |--------------------------------------------------------------------------
    */
    'gateways' => [

        'moolre' => [
            'label' => 'Moolre',
            'base_url' => env('MOOLRE_BASE_URL', 'https://api.moolre.com/open/transact'),
            'api_user' => env('MOOLRE_API_USER'),
            'pub_key' => env('MOOLRE_API_PUBKEY'),
            'merchant_id' => env('MOOLRE_MERCHANT_ID'),
            'webhook_secret' => env('MOOLRE_WEBHOOK_SECRET'),
        ],

        /*
        |----------------------------------------------------------------------
        | Transflow (ITConsortium)
        |----------------------------------------------------------------------
        |
        | Redirect-based gateway that supports both MoMo (all networks) and
        | card payments via a hosted checkout page.
        |
        | The base URL is automatically chosen based on APP_ENV:
        |   local/staging → UAT endpoint
        |   production    → live endpoint
        |
        | Override by setting TRANSFLOW_BASE_URL in .env if you need to force
        | a specific URL (e.g. to test against live from a staging box).
        |
        */
        'transflow' => [
            'label' => 'Transflow',
            // Auto-selects UAT or live based on APP_ENV; override with TRANSFLOW_BASE_URL
            'base_url' => env('TRANSFLOW_BASE_URL',
                env('APP_ENV') === 'production'
                    ? 'https://apis.itcsrvc.com/checkout'     // live
                    : 'https://apisuat.itcsrvc.com/checkout'  // uat
            ),
            'api_key' => env('TRANSFLOW_API_KEY'),
            'transflow_id' => env('TRANSFLOW_ID'),
            'merchant_product_id' => env('TRANSFLOW_MERCHANT_PRODUCT_ID'),
        ],

    ],

];
