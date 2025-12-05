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

    public function description(): string
    {
        return match ($this) {
            self::DRAFT => "Interview is draft.",
            self::SCHEDULED => "Interview is scheduled.",
            self::IN_PROGRESS => "Interview is in progress.",
            self::CANCELLED => "Interview is cancelled.",
            self::COMPLETED => "Interview is completed.",
            self::EVALUATED => "Interview is evaluated.",
        };
    }
}
