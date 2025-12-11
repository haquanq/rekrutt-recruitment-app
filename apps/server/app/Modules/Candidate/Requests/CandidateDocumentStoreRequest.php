<?php

namespace App\Modules\Candidate\Requests;

use App\Modules\Candidate\Abstracts\BaseCandidateDocumentRequest;
use App\Modules\Candidate\Enums\CandidateStatus;
use App\Modules\Candidate\Models\CandidateDocument;
use App\Modules\Candidate\Rules\CandidateExistsWithStatusRule;
use Illuminate\Support\Facades\Gate;

class CandidateDocumentStoreRequest extends BaseCandidateDocumentRequest
{
    public function rules(): array
    {
        return [
            ...parent::rules(),
            ...[
                /**
                 * Id of Candidate
                 * @example 1
                 */
                "candidate_id" => ["required", "integer", new CandidateExistsWithStatusRule(CandidateStatus::PENDING)],
            ],
        ];
    }

    public function authorize(): bool
    {
        Gate::authorize("create", CandidateDocument::class);
        return true;
    }
}
