<?php

declare(strict_types=1);

namespace App\Enums;

enum BookingType: string
{
    case Meal = 'meal';
    case Event = 'event';
}
