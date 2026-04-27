<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Cache;

class ContactMessage extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'inquiry_type',
        'message',
        'status',
        'response_notes',
        'responded_at',
        'responded_by_id',
        'ip_address',
    ];

    protected function casts(): array
    {
        return [
            'responded_at' => 'datetime',
        ];
    }

    public function respondedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'responded_by_id');
    }

    protected static function booted(): void
    {
        // Clear the shared unread-count cache whenever a message is created or its status changes.
        $forget = fn () => Cache::forget('contact_messages.new_count');
        static::created($forget);
        static::updated($forget);
        static::deleted($forget);
    }
}
