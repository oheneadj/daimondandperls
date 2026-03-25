<?php

declare(strict_types=1);

namespace App\Enums;

enum PaymentGatewayStatus: string
{
    case Initiated = 'initiated';
    case Pending = 'pending';
    case Successful = 'successful';
    case Failed = 'failed';
    case Refunded = 'refunded';
}
