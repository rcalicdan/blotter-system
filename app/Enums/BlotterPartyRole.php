<?php

namespace App\Enums;

enum BlotterPartyRole: string
{
    case Complainant = 'complainant';
    case Respondent  = 'respondent';
    case Witness     = 'witness';
}