<?php

namespace App\Modules\Proposal\Enums;

enum ProposalStatus: string
{
    case DRAFT = "DRAFT";
    case PENDING = "PENDING";
    case APPROVED = "APPROVED";
    case REJECTED = "REJECTED";

    public function description(): string
    {
        return match ($this) {
            self::DRAFT => "Proposal is draft",
            self::PENDING => "Proposal is submitted",
            self::APPROVED => "Proposal is approved",
            self::REJECTED => "Proposal is rejected",
        };
    }
}
