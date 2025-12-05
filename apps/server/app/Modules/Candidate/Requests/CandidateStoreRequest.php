<?php

namespace App\Modules\Candidate\Requests;

use App\Modules\Candidate\Abstracts\BaseCandidateRequest;
use App\Modules\Candidate\Enums\CandidateStatus;
use App\Modules\Candidate\Models\Candidate;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;

class CandidateStoreRequest extends BaseCandidateRequest
{
    public function rules(): array
    {
        return [
            /**
             * Initial status (generated automatically)
             * @ignoreParam
             */
            "status" => ["required", Rule::enum(CandidateStatus::class)->only(CandidateStatus::PENDING)],
        ];
    }

    public function authorize(): bool
    {
        Gate::authorize("create", Candidate::class);
        return true;
    }
}
