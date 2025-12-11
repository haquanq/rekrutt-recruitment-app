<?php

namespace App\Modules\Proposal\Requests;

use App\Modules\Proposal\Abstracts\BaseProposalRequest;
use Illuminate\Support\Facades\Gate;

class ProposalUpdateRequest extends BaseProposalRequest
{
    public function authorize(): bool
    {
        Gate::authorize("update", $this->getQueriedProposalOrFail());
        return true;
    }
}
