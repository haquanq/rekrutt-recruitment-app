<?php

namespace App\Modules\Candidate\Enums;

enum CandidateStatus: string
{
    case NEW = "NEW";
    case INTERVIEWING = "INTERVIEWING";
    case HIRED = "HIRED";
    case ARCHIVED = "ARCHIVED";
    case BLACKLISTED = "BLACKLISTED";
}
