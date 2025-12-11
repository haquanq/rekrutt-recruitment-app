<?php

namespace App\Modules\Candidate\Abstracts;

use App\Abstracts\BaseFormRequest;
use App\Modules\Candidate\Models\CandidateDocument;
use Illuminate\Validation\Rules\File as FileRule;

abstract class BaseCandidateDocumentRequest extends BaseFormRequest
{
    protected ?CandidateDocument $candidateDocument = null;

    public function getQueriedCandidateDocumentOrFail(string $param = "id"): CandidateDocument
    {
        if ($this->candidateDocument === null) {
            $this->candidateDocument = CandidateDocument::findOrFail($this->route($param));
        }

        return $this->candidateDocument;
    }

    public function rules(): array
    {
        return [
            /**
             * Document file (.pdf, .docx, .doc). Max: 5MB
             */
            "document" => ["required", FileRule::types(["pdf", "docx", "doc"])->max(5 * 1024)],
            /**
             * Notes
             * @example "CV - English"
             */
            "notes" => ["string", "max:500"],
        ];
    }
}
