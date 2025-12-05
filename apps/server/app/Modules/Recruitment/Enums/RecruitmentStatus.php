<?php

namespace App\Modules\Recruitment\Enums;

enum RecruitmentStatus: string
{
    case DRAFT = "DRAFT";
    case SCHEDULED = "SCHEDULED";
    case PUBLISHED = "PUBLISHED";
    case CLOSED = "CLOSED";
    case COMPLETED = "COMPLETED";

    public function description(): string
    {
        return match ($this) {
            self::DRAFT => "Recruitment is draft.",
            self::SCHEDULED => "Recruitment is scheduled.",
            self::PUBLISHED => "Recruitment is published.",
            self::CLOSED => "Recruitment is closed.",
            self::COMPLETED => "Recruitment is completed.",
        };
    }
}
