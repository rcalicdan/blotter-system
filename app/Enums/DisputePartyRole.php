<?php

declare(strict_types=1);

namespace App\Enums;

enum DisputePartyRole: string
{
    case Complainant = 'complainant';
    case Respondent = 'respondent';
}
