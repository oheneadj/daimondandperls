<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\PaymentMethod;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CustomerPaymentMethod extends Model
{
    /** @use HasFactory<\Database\Factories\CustomerPaymentMethodFactory> */
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'type',
        'label',
        'provider',
        'account_number',
        'account_name',
        'is_default',
        'verified_at',
        'verification_code',
    ];

    protected function casts(): array
    {
        return [
            'type' => PaymentMethod::class,
            'is_default' => 'boolean',
            'verified_at' => 'datetime',
        ];
    }

    public function isVerified(): bool
    {
        return ! is_null($this->verified_at);
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }
}
