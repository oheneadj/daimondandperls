<?php

declare(strict_types=1);

namespace App\Enums;

enum BookingStatus: string
{
    case Pending = 'pending';
    case Confirmed = 'confirmed';
    case InPreparation = 'in_preparation';
    case ReadyForDelivery = 'ready_for_delivery';
    case Completed = 'completed';
    case Cancelled = 'cancelled';
}
