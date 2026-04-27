<?php

declare(strict_types=1);

if (! function_exists('dpc_setting')) {
    /**
     * Read a single setting value by key.
     *
     * This hits the cache (never the DB after the first request) by going
     * through Setting::getCached(), which loads and caches every setting row
     * in one query. Use this everywhere instead of Setting::where('key', ...).
     *
     * Example: dpc_setting('business_whatsapp', '233244203181')
     */
    function dpc_setting(string $key, mixed $default = null): mixed
    {
        return \App\Models\Setting::getCached()->get($key)?->value ?? $default;
    }
}
