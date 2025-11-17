<?php

namespace App\Modules\Proposal\Enums;

enum ProposalStatus: string
{
    case DRAFT = "DRAFT";
    case REVIEWING = "REVIEWING";
    case APPROVED = "APPROVED";
    case REJECTED = "REJECTED";
}
