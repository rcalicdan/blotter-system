<?php

namespace App\Enums;

enum DisputePartyRole: string
{
    case Complainant = 'complainant';
    case Respondent  = 'respondent';
}