<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PaymentLog extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'payment_id',
        'gateway',
        'direction',
        'event',
        'booking_reference',
        'level',
        'status',
        'gateway_ref',
        'error_code',
        'error_message',
        'network',
        'payer_number',
        'raw_request',
        'raw_response',
        'payload',
        'http_status',
        'duration_ms',
        'ip_address',
        'created_at',
    ];

    protected function casts(): array
    {
        return [
            'raw_request' => 'array',
            'raw_response' => 'array',
            'payload' => 'array',
            'created_at' => 'datetime',
        ];
    }

    public function payment(): BelongsTo
    {
        return $this->belongsTo(Payment::class);
    }
}
