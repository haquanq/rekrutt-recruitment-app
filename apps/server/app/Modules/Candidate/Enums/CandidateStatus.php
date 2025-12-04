<?php

namespace App\Modules\Candidate\Enums;

enum CandidateStatus: string
{
    case PENDING = "PENDING";
    case PROCESSING = "PROCESSING";
    case EMPLOYED = "EMPLOYED";
    case ARCHIVED = "ARCHIVED";
    case BLACKLISTED = "BLACKLISTED";

    public function description(): string
    {
        return match ($this) {
            self::PENDING => "Candidate is ready",
            self::PROCESSING => "Candidate is being processed",
            self::EMPLOYED => "Candidate is employed",
            self::ARCHIVED => "Candidate is archived",
            self::BLACKLISTED => "Candidate is blacklisted",
        };
    }
}
