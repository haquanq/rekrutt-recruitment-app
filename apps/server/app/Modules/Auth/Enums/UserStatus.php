<?php

namespace App\Modules\Auth\Enums;

enum UserStatus: string
{
    case ACTIVE = "ACTIVE";
    case SUSPENDED = "SUSPENDED";
    case RETIRED = "RETIRED";
}
