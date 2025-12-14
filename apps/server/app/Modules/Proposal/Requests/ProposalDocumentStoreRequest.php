<?php

namespace App\Modules\Proposal\Requests;

use App\Modules\Proposal\Abstracts\BaseProposalDocumentRequest;
use App\Modules\Proposal\Enums\ProposalStatus;
use App\Modules\Proposal\Models\Proposal;
use App\Modules\Proposal\Models\ProposalDocument;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Validator;

class ProposalDocumentStoreRequest extends BaseProposalDocumentRequest
{
    public ?Proposal $proposal = null;

    public function rules(): array
    {
        return [
            ...parent::rules(),
            ...[
                /**
                 * Id of Proposal to which the document belongs
                 * @example 1
                 */
                "proposal_id" => ["required", "integer"],
            ],
        ];
    }

    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $validator) {
            if ($validator->errors()->isNotEmpty()) {
                return;
            }

            if (!$this->proposal) {
                $validator->errors()->add("proposal_id", "Proposal does not exist.");
            } elseif (!collect([ProposalStatus::DRAFT, ProposalStatus::REJECTED])->contains($this->proposal->status)) {
                $validator->errors()->add("proposal_id", "Cannot add document to this proposal.");
            }
        });
    }

    public function authorize(): bool
    {
        Gate::authorize("create", [ProposalDocument::class, $this->proposal]);
        return true;
    }

    public function prepareForValidation(): void
    {
        parent::prepareForValidation();
        $this->proposal = Proposal::find($this->input("proposal_id"));
    }
}
