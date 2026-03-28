<?php

declare(strict_types=1);

namespace App\Enums;

enum DisputeStatus: string
{
    case Filed = 'filed';
    case Ongoing = 'ongoing';
    case Settled = 'settled';
    case Dismissed = 'dismissed';
    case Escalated = 'escalated';
}
