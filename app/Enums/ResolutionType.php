<?php

namespace App\Enums;

enum ResolutionType: string
{
    case Settled   = 'settled';
    case Dismissed = 'dismissed';
    case Escalated = 'escalated';
}