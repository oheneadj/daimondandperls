<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

class Setting extends Model
{
    protected $fillable = [
        'key',
        'value',
        'type',
        'label',
        'group',
    ];

    protected function casts(): array
    {
        return [
            'value' => 'string',
            'type' => \App\Enums\SettingType::class,
        ];
    }

    public function getValueAttribute($value)
    {
        return match ($this->type) {
            \App\Enums\SettingType::Integer => (int) $value,
            \App\Enums\SettingType::Boolean => (bool) $value,
            \App\Enums\SettingType::Json => json_decode($value, true),
            default => $value,
        };
    }

    public function setValueAttribute($value): void
    {
        $this->attributes['value'] = is_array($value) ? json_encode($value) : (string) $value;
    }

    /**
     * Load all settings from cache, keyed by their key column.
     *
     * I cache every row in one shot so the rest of the app can read any
     * setting without touching the database. The cache lives forever and
     * is cleared automatically whenever a setting is saved or deleted (see booted()).
     *
     * Usage: Setting::getCached()->get('business_name')?->value
     * Prefer the dpc_setting() helper over calling this directly.
     */
    public static function getCached(): Collection
    {
        return Cache::rememberForever('app_settings', fn () => static::all()->keyBy('key'));
    }

    /**
     * Clear the settings cache whenever a setting row changes.
     *
     * This covers updateOrCreate(), save(), and delete() — anything that
     * goes through Eloquent. The next call to getCached() will rebuild it.
     *
     * Note: raw query-builder updates (Setting::where(...)->update()) bypass
     * Eloquent events and will NOT clear the cache automatically.
     */
    protected static function booted(): void
    {
        $forget = fn () => Cache::forget('app_settings');
        static::saved($forget);
        static::deleted($forget);
    }
}
