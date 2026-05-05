<?php

declare(strict_types=1);

namespace App\Models;

use Database\Factories\BookingWindowFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class BookingWindow extends Model
{
    /** @use HasFactory<BookingWindowFactory> */
    use HasFactory;

    protected $fillable = ['name', 'delivery_day', 'cutoff_day', 'cutoff_time'];

    protected function casts(): array
    {
        return [
            'delivery_day' => 'integer',
            'cutoff_day' => 'integer',
        ];
    }

    public function packages(): BelongsToMany
    {
        return $this->belongsToMany(Package::class);
    }
}
