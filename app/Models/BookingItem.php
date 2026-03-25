<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BookingItem extends Model
{
    protected $fillable = [
        'booking_id',
        'package_id',
        'price',
        'quantity',
    ];

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }

    public function package()
    {
        return $this->belongsTo(Package::class);
    }
}
