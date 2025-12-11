<?php

namespace App\Modules\Proposal\Abstracts;

use App\Abstracts\BaseFormRequest;
use App\Modules\Position\Rules\PositionExistsInCurrentUserDepartmentRule;
use Illuminate\Validation\Rule;

abstract class BaseProposalRequest extends BaseFormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            /**
             * Title
             * @example Seniors Software Engineer with 5+ Years For New Projects
             */
            "title" => ["bail", "required", "string", "max:100", Rule::unique("proposal")->ignore($this->route("id"))],
            /**
             * Description
             * @example We're Hiring: Senior Software Engineer with 5+ Years in Scalable Cloud Architecture for New Projects
             */
            "description" => ["required", "string", "max:500"],
            /**
             * Number of hires need
             * @example 2
             */
            "target_hires" => ["required", "integer", "max:1000"],
            /**
             * Min salary (USD)
             * @example 120000
             */
            "min_salary" => ["required", "integer", "max:1000000"],
            /**
             * Max salary (USD)
             * @example 200000
             */
            "max_salary" => ["required", "integer", "max:1000000"],
            /**
             * Id of Position in current User's department
             * @example 1
             */
            "position_id" => ["required", "integer:strict", new PositionExistsInCurrentUserDepartmentRule()],
            /**
             * Id of ContractType
             * @example 1
             */
            "contract_type_id" => ["required", "integer:strict", Rule::exists("contract_type", "id")],
            /**
             * Id of EducationLevel
             * @example 1
             */
            "education_level_id" => ["required", "integer:strict", Rule::exists("education_level", "id")],
            /**
             * Id of ExperienceLevel
             * @example 1
             */
            "experience_level_id" => ["required", "integer:strict", Rule::exists("experience_level", "id")],
        ];
    }
}
