<?php

namespace App\Modules\Proposal\Requests;

use App\Modules\Proposal\Abstracts\BaseProposalDocumentRequest;
use Illuminate\Support\Facades\Gate;

class ProposalDocumentUpdateRequest extends BaseProposalDocumentRequest
{
    public function rules(): array
    {
        return [
            /**
             * Description
             * @example "Requirements"
             */
            "description" => ["string", "max:500"],
        ];
    }

    public function authorize(): bool
    {
        Gate::authorize("update", $this->getQueriedProposalDocumentOrFail());
        return true;
    }
}
