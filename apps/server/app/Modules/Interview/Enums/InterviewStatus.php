<?php

namespace App\Modules\Interview\Enums;

enum InterviewStatus: string
{
    case DRAFT = "DRAFT";
    case SCHEDULED = "SCHEDULED";
    case IN_PROGRESS = "IN_PROGRESS";
    case CANCELLED = "CANCELLED";
    case COMPLETED = "COMPLETED";
    case EVALUATED = "EVALUATED";
}
