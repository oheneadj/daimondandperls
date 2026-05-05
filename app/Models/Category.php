<?php

declare(strict_types=1);

namespace App\Models;

use Database\Factories\CategoryFactory;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Cache;

class Category extends Model
{
    /** @use HasFactory<CategoryFactory> */
    use HasFactory, SoftDeletes;

    protected $fillable = ['name', 'slug'];

    public function packages(): BelongsToMany
    {
        return $this->belongsToMany(Package::class);
    }

    /**
     * Return the first 4 categories (alphabetically) from cache.
     */
    public static function getCachedFooter(): Collection
    {
        return Cache::rememberForever('categories.footer', fn () => static::orderBy('name')->limit(4)->get());
    }

    protected static function booted(): void
    {
        $forget = fn () => Cache::forget('categories.footer');
        static::saved($forget);
        static::deleted($forget);
    }
}
