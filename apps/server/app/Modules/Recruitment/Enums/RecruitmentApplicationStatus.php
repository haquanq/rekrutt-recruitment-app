<?php

namespace App\Modules\Recruitment\Enums;

enum RecruitmentApplicationStatus: string
{
    case PENDING = "PENDING";
    case INTERVIEWING = "INTERVIEWING";
    case OFFERING = "OFFERING";
    case DECLINED = "DECLINED";
    case ACCEPTED = "ACCEPTED";
}
