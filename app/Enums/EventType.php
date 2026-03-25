<?php

declare(strict_types=1);

namespace App\Enums;

enum EventType: string
{
    case Wedding = 'wedding';
    case Birthday = 'birthday';
    case Corporate = 'corporate';
    case Funeral = 'funeral';
    case Party = 'party';
    case Other = 'other';
}
