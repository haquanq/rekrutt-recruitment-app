<?php

namespace App\Modules\Proposal\Abstracts;

use App\Abstracts\BaseFormRequest;
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
            "candidate_id" => ["required", "integer", "exists:candidate,id"],
            "document" => ["required", FileRule::types(["pdf", "docx", "doc"])->max(5 * 1024)],
            "note" => ["string", "max:500"],
        ];
    }
}
