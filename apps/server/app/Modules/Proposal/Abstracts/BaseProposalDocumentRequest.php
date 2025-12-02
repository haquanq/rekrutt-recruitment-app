<?php

namespace App\Modules\Proposal\Abstracts;

use App\Abstracts\BaseFormRequest;
use App\Modules\Proposal\Enums\ProposalStatus;
use App\Modules\Proposal\Rules\ProposalExistsWithStatusRule;
use Illuminate\Validation\Rules\File as FileRule;

abstract class BaseProposalDocumentRequest extends BaseFormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            /**
             * Id of Proposal where document will be attached
             * @example 1
             */
            "proposal_id" => ["required", "integer", new ProposalExistsWithStatusRule(ProposalStatus::DRAFT)],
            /**
             * Document file (.pdf, .docx, .doc).
             * Max: 5MB
             */
            "document" => ["required", FileRule::types(["pdf", "docx", "doc"])->max(5 * 1024)],
            /**
             * Description
             * @example "Requirements"
             */
            "description" => ["string", "max:500"],
        ];
    }
}
