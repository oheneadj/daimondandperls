<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\BookingStatus;
use App\Enums\EventType;
use App\Enums\PaymentStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Booking extends Model
{
    /** @use HasFactory<\Database\Factories\BookingFactory> */
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'reference',
        'customer_id',
        'event_date',
        'event_start_time',
        'event_end_time',
        'event_type',
        'event_type_other',
        'total_amount',
        'status',
        'payment_status',
        'admin_notes',
        'cancelled_reason',
        'confirmed_by',
        'confirmed_at',
        'completed_at',
        'cancelled_at',
    ];

    protected function casts(): array
    {
        return [
            'event_date' => 'date',
            'total_amount' => 'decimal:2',
            'status' => BookingStatus::class,
            'payment_status' => PaymentStatus::class,
            'event_type' => EventType::class,
            'confirmed_at' => 'datetime',
            'completed_at' => 'datetime',
            'cancelled_at' => 'datetime',
        ];
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(BookingItem::class);
    }

    public function payment(): HasOne
    {
        return $this->hasOne(Payment::class);
    }

    public function confirmedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'confirmed_by');
    }

    public function bookingNotificationLogs(): HasMany
    {
        return $this->hasMany(BookingNotificationLog::class);
    }
}
