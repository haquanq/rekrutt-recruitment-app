<?php

namespace App\Modules\Proposal\Abstracts;

use App\Abstracts\BaseFormRequest;
use App\Modules\Proposal\Models\ProposalDocument;
use Illuminate\Validation\Rules\File as FileRule;

abstract class BaseProposalDocumentRequest extends BaseFormRequest
{
    protected ?ProposalDocument $proposalDocument = null;

    public function getQueriedProposalDocumentOrFail(string $param = "id"): ProposalDocument
    {
        if ($this->proposalDocument === null) {
            $this->proposalDocument = ProposalDocument::findOrFail($this->route($param));
        }

        return $this->proposalDocument;
    }

    public function rules(): array
    {
        return [
            /**
             * Document file (.pdf, .docx, .doc).
             * Max: 5MB
             */
            "document" => ["required", FileRule::types(["pdf", "docx", "doc"])->max(5 * 1024)],
            /**
             * Notes
             * @example "Requirements"
             */
            "notes" => ["string", "max:500"],
        ];
    }
}
