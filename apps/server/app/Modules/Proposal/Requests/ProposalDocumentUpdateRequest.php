<?php

namespace App\Modules\Proposal\Requests;

use App\Modules\Proposal\Abstracts\BaseProposalDocumentRequest;
use App\Modules\Proposal\Models\ProposalDocument;
use Illuminate\Support\Facades\Gate;

class ProposalDocumentUpdateRequest extends BaseProposalDocumentRequest
{
    public ProposalDocument $proposalDocument;

    /**
     * @bodyParam proposal_id integer required The proposal ID. Example: 123
     */
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
        Gate::authorize("update", $this->proposalDocument);
        return true;
    }

    public function prepareForValidation(): void
    {
        parent::prepareForValidation();
        $this->proposalDocument = ProposalDocument::with("proposal")->findOrFail($this->route("id"));
    }
}
