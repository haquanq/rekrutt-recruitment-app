<?php

namespace App\Modules\Candidate\Requests;

use App\Modules\Candidate\Abstracts\BaseCandidateExperienceRequest;
use App\Modules\Candidate\Enums\CandidateStatus;
use App\Modules\Candidate\Models\CandidateExperience;
use App\Modules\Candidate\Rules\CandidateExistsWithStatusRule;
use Illuminate\Support\Facades\Gate;

class CandidateExperienceStoreRequest extends BaseCandidateExperienceRequest
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
                "candidate_id" => [
                    "required",
                    "integer:strict",
                    new CandidateExistsWithStatusRule(CandidateStatus::PENDING),
                ],
            ],
        ];
    }

    public function authorize(): bool
    {
        Gate::authorize("create", CandidateExperience::class);
        return true;
    }
}
