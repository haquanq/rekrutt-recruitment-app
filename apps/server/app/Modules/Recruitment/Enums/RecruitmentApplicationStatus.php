<?php

namespace App\Modules\Recruitment\Enums;

enum RecruitmentApplicationStatus: string
{
    case PENDING = "PENDING";
    case INTERVIEWING = "INTERVIEWING";
    case OFFER_PENDING = "OFFER_PENDING";
    case OFFER_REJECTED = "OFFER_REJECTED";
    case OFFER_ACCEPTED = "OFFER_ACCEPTED";
    case WITHDRAWN = "WITHDRAWN";
    case DISCARDED = "DISCARDED";
}
