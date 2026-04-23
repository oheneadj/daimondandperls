<?php

declare(strict_types=1);

namespace App\Enums;

enum PaymentGateway: string
{
    case Paystack = 'paystack';
    case Flutterwave = 'flutterwave';
    case Hubtel = 'hubtel';
    case Moolre = 'moolre';
    case Transflow = 'transflow';
    case Manual = 'manual';
}
