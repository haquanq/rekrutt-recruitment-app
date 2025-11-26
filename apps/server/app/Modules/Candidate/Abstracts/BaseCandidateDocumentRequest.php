<?php

namespace App\Modules\Candidate\Abstracts;

use App\Abstracts\BaseFormRequest;
use App\Modules\Candidate\Models\Candidate;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules\File as FileRule;

abstract class BaseCandidateDocumentRequest extends BaseFormRequest
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
