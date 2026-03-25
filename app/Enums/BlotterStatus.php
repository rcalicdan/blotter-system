<?php

namespace App\Enums;

enum BlotterStatus: string
{
    case Open     = 'open';
    case Closed   = 'closed';
    case Referred = 'referred';
}