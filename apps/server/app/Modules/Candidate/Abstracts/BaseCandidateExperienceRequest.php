<?php

namespace App\Modules\Candidate\Abstracts;

use App\Abstracts\BaseFormRequest;

abstract class BaseCandidateExperienceRequest extends BaseFormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            "from_date" => ["required", "date", "before:today"],
            "to_date" => ["required", "date", "before:today"],
            "employer_name" => ["required", "string", "max:100"],
            "employer_description" => ["string", "max:500"],
            "position_title" => ["required", "string", "max:100"],
            "position_duty" => ["required", "string", "max:500"],
            "note" => ["string", "max:500"],
            "candidate_id" => ["required", "integer:strict", "exists:candidate,id"],
        ];
    }
}
