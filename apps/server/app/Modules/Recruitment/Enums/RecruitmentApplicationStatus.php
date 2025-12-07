<?php

namespace App\Modules\Recruitment\Enums;

enum RecruitmentApplicationStatus: string
{
    case PENDING = "PENDING";
    case INTERVIEW_PLANNING = "INTERVIEW_PLANNING";
    case INTERVIEW_SCHEDULED = "INTERVIEW_SCHEDULED";
    case INTERVIEW_COMPLETED = "INTERVIEW_COMPLETED";
    case OFFER_PENDING = "OFFER_PENDING";
    case OFFER_REJECTED = "OFFER_REJECTED";
    case OFFER_ACCEPTED = "OFFER_ACCEPTED";
    case WITHDRAWN = "WITHDRAWN";
    case DISCARDED = "DISCARDED";

    public function description(): string
    {
        return match ($this) {
            self::PENDING => "Recruitmen application is pending.",
            self::INTERVIEW_PLANNING => "Recruitment application interviews are being planned.",
            self::INTERVIEW_SCHEDULED => "Recruitment application interviews are scheduled.",
            self::INTERVIEW_COMPLETED => "Recruitment application interviews are completed.",
            self::OFFER_PENDING => "Recruitment application offer is pending.",
            self::OFFER_REJECTED => "Recruitment application offer is rejected.",
            self::OFFER_ACCEPTED => "Recruitment application offer is accepted.",
            self::WITHDRAWN => "Recruitment application is withdrawn.",
            self::DISCARDED => "Recruitment application is discarded.",
        };
    }
}
