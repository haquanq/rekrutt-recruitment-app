<?php

namespace App\Modules\Proposal\Requests;

use App\Modules\Proposal\Abstracts\BaseProposalDocumentRequest;
use App\Modules\Proposal\Models\ProposalDocument;
use Illuminate\Support\Facades\Gate;

class ProposalDocumentDestroyRequest extends BaseProposalDocumentRequest
{
    public function rules(): array
    {
        return [];
    }

    public function authorize(): bool
    {
        Gate::authorize("delete", $this->getQueriedProposalDocumentOrFail());
        return true;
    }
}
