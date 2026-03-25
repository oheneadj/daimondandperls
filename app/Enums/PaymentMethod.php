<?php

declare(strict_types=1);

namespace App\Enums;

enum PaymentMethod: string
{
    case MobileMoney = 'mobile_money';
    case Card = 'card';
    case BankTransfer = 'bank_transfer';
    case Cash = 'cash';
}
