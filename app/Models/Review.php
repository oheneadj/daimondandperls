<?php

declare(strict_types=1);

namespace App\Models;

use Database\Factories\ReviewFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Review extends Model
{
    /** @use HasFactory<ReviewFactory> */
    use HasFactory;

    protected $fillable = [
        'booking_id', 'customer_id', 'token', 'stars',
        'author_name', 'reviewer_phone', 'message',
        'is_approved', 'points_awarded',
        'friend_name', 'friend_phone', 'friend_sms_sent_at',
        'submitted_at',
    ];

    protected function casts(): array
    {
        return [
            'submitted_at' => 'datetime',
            'friend_sms_sent_at' => 'datetime',
            'is_approved' => 'boolean',
            'stars' => 'integer',
        ];
    }

    public function booking(): BelongsTo
    {
        return $this->belongsTo(Booking::class);
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function scopeApproved(Builder $query): void
    {
        $query->where('is_approved', true);
    }

    public static function findByToken(string $token): ?self
    {
        return static::where('token', $token)->first();
    }
}
