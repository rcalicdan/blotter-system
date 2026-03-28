<?php

declare(strict_types=1);

namespace App\Enums;

enum ResolutionType: string
{
    case Settled = 'settled';
    case Dismissed = 'dismissed';
    case Escalated = 'escalated';
}
