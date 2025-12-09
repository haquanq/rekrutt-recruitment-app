<?php

namespace App\Modules\Proposal\Requests;

use App\Modules\Proposal\Abstracts\BaseProposalDocumentRequest;
use App\Modules\Proposal\Enums\ProposalStatus;
use App\Modules\Proposal\Models\Proposal;
use App\Modules\Proposal\Models\ProposalDocument;
use App\Modules\Proposal\Rules\ProposalExistsWithStatusRule;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Validator;

class ProposalDocumentStoreRequest extends BaseProposalDocumentRequest
{
    public $proposal;

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
        $validator->addRules(["proposal_id" => [new ProposalExistsWithStatusRule(ProposalStatus::DRAFT)]]);
    }

    public function authorize(): bool
    {
        Gate::authorize("create", [ProposalDocument::class, $this->proposal]);
        return true;
    }

    public function prepareForValidation(): void
    {
        parent::prepareForValidation();
        $this->proposal = Proposal::findOrFail($this->input("proposal_id"));
    }
}
