<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

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
}
