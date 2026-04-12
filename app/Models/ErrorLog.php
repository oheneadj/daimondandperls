<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ErrorLog extends Model
{
    protected $fillable = [
        'source',
        'context',
        'level',
        'booking_reference',
        'error_code',
        'message',
        'network',
        'payer_number',
        'payload',
        'resolved',
        'resolution_note',
        'resolved_by',
        'resolved_at',
    ];

    protected function casts(): array
    {
        return [
            'payload' => 'array',
            'resolved' => 'boolean',
            'resolved_at' => 'datetime',
        ];
    }

    public function resolvedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'resolved_by');
    }

    public function booking(): BelongsTo
    {
        return $this->belongsTo(Booking::class, 'booking_reference', 'reference');
    }
}
