<?php

namespace App\Modules\Candidate\Abstracts;

use App\Abstracts\BaseFormRequest;
use App\Modules\Candidate\Models\Candidate;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules\File as FileRule;

abstract class BaseCandidateDocumentRequest extends BaseFormRequest
{
    public function rules(): array
    {
        return [
            /**
             * Document file (.pdf, .docx, .doc).
             * Max: 5MB
             */
            "document" => ["required", FileRule::types(["pdf", "docx", "doc"])->max(5 * 1024)],
            /**
             * Description
             * @example "CV - English"
             */
            "description" => ["string", "max:500"],
        ];
    }
}
