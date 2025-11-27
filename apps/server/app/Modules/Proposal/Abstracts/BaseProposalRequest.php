<?php

namespace App\Modules\Proposal\Abstracts;

use App\Abstracts\BaseFormRequest;

abstract class BaseProposalRequest extends BaseFormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            "title" => ["required", "string", "max:100"],
            "description" => ["required", "string", "max:500"],
            "target_hires" => ["required", "integer", "max:1000"],
            "min_salary" => ["required", "integer", "max:1000000"],
            "max_salary" => ["required", "integer", "max:1000000"],
            "created_by_user_id" => ["required", "integer"],
            "position_id" => ["required", "integer", "exists:position,id"],
            "contract_type_id" => ["required", "integer", "exists:contract_type,id"],
            "education_level_id" => ["required", "integer", "exists:education_level,id"],
            "experience_level_id" => ["required", "integer", "exists:experience_level,id"],
        ];
    }
}
