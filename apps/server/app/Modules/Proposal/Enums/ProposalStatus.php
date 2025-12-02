<?php

namespace App\Modules\Proposal\Enums;

enum ProposalStatus: string
{
    case DRAFT = "DRAFT";
    case PENDING = "PENDING";
    case APPROVED = "APPROVED";
    case REJECTED = "REJECTED";
}
