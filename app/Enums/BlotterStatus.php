<?php

declare(strict_types=1);

namespace App\Enums;

enum BlotterStatus: string
{
    case Open = 'open';
    case Closed = 'closed';
    case Referred = 'referred';
}
