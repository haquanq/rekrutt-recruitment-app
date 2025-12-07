<?php

namespace App\Modules\Interview\Enums;

enum InterviewStatus: string
{
    case DRAFT = "DRAFT";
    case SCHEDULED = "SCHEDULED";
    case IN_PROGRESS = "IN_PROGRESS";
    case UNDER_EVALUATION = "UNDER_EVALUATION";
    case COMPLETED = "COMPLETED";
    case CANCELLED = "CANCELLED";

    public function description(): string
    {
        return match ($this) {
            self::DRAFT => "Interview is draft.",
            self::SCHEDULED => "Interview is scheduled.",
            self::IN_PROGRESS => "Interview is in progress.",
            self::UNDER_EVALUATION => "Interview is under evaluation.",
            self::COMPLETED => "Interview is completed.",
            self::CANCELLED => "Interview is cancelled.",
        };
    }
}
