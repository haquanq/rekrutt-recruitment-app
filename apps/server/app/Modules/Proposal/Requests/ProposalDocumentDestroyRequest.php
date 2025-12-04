<?php

namespace App\Modules\Proposal\Requests;

use App\Modules\Proposal\Abstracts\BaseProposalRequest;
use App\Modules\Proposal\Models\ProposalDocument;
use Illuminate\Support\Facades\Gate;

class ProposalDocumentDestroyRequest extends BaseProposalRequest
{
    public ProposalDocument $proposalDocument;

    public function rules(): array
    {
        return [];
    }

    public function authorize(): bool
    {
        Gate::authorize("delete", $this->proposalDocument);
        return true;
    }

    public function prepareForValidation(): void
    {
        parent::prepareForValidation();
        $this->proposal = ProposalDocument::with("proposal")->findOrFail($this->route("id"));
    }
}
