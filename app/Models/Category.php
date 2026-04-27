<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Cache;

class Category extends Model
{
    /** @use HasFactory<\Database\Factories\CategoryFactory> */
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'slug',
        'booking_window_enabled',
        'delivery_day',
        'cutoff_day',
        'cutoff_time',
    ];

    protected function casts(): array
    {
        return [
            'booking_window_enabled' => 'boolean',
            'delivery_day' => 'integer',
            'cutoff_day' => 'integer',
        ];
    }

    public function hasBookingWindow(): bool
    {
        return $this->booking_window_enabled
            && $this->delivery_day !== null
            && $this->cutoff_day !== null
            && $this->cutoff_time !== null;
    }

    public function packages(): HasMany
    {
        return $this->hasMany(Package::class);
    }

    /**
     * Return the first 4 categories (alphabetically) from cache.
     *
     * Used in the site footer on every page, so we cache it forever to avoid
     * a DB hit on every request. The cache is cleared automatically when any
     * category is created, updated, or deleted (see booted()).
     */
    public static function getCachedFooter(): Collection
    {
        return Cache::rememberForever('categories.footer', fn () => static::orderBy('name')->limit(4)->get());
    }

    /**
     * Clear the footer category cache whenever a category row changes.
     *
     * Same pattern as Setting::booted() — Eloquent events only, raw
     * query-builder updates won't trigger this.
     */
    protected static function booted(): void
    {
        $forget = fn () => Cache::forget('categories.footer');
        static::saved($forget);
        static::deleted($forget);
    }
}
