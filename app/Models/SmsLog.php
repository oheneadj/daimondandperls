<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SmsLog extends Model
{
    protected $fillable = [
        'booking_id',
        'message_id',
        'to',
        'message',
        'status',
        'response',
    ];

    protected function casts(): array
    {
        return [
            'response' => 'array',
        ];
    }
}
