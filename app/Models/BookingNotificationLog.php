<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BookingNotificationLog extends Model
{
    protected $fillable = [
        'booking_id',
        'channel',
        'recipient',
        'template',
        'status',
        'provider_ref',
        'sent_at',
        'error_message',
    ];

    protected function casts(): array
    {
        return [
            'sent_at' => 'datetime',
            'channel' => \App\Enums\NotificationChannel::class,
            'status' => \App\Enums\NotificationStatus::class,
        ];
    }

    public function booking(): BelongsTo
    {
        return $this->belongsTo(Booking::class);
    }
}
