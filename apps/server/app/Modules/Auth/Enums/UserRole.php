<?php

namespace App\Modules\Auth\Enums;

enum UserRole: string
{
    case ADMIN = "ADMIN";
    case MANAGER = "MANAGER";
    case HIRING_MANAGER = "HIRING_MANAGER";
    case RECRUITER = "RECRUITER";
    case EXECUTIVE = "EXECUTIVE";
    case INTERVIEWER = "INTERVIEWER";
}
