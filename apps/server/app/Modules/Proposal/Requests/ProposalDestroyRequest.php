<?php

namespace App\Modules\Proposal\Requests;

use App\Modules\Proposal\Abstracts\BaseProposalRequest;
use Illuminate\Support\Facades\Gate;

class ProposalDestroyRequest extends BaseProposalRequest
{
    public function rules(): array
    {
        return [];
    }

    public function authorize(): bool
    {
        Gate::authorize("delete", $this->proposal);
        return true;
    }
}
