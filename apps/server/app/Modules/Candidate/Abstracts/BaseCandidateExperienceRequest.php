<?php

namespace App\Modules\Candidate\Abstracts;

use App\Abstracts\BaseFormRequest;
use App\Modules\Candidate\Models\CandidateExperience;

abstract class BaseCandidateExperienceRequest extends BaseFormRequest
{
    protected ?CandidateExperience $candidateExperience = null;

    public function getQueriedCandidateExperienceOrFail(string $param = "id"): CandidateExperience
    {
        if ($this->candidateExperience === null) {
            $this->candidateExperience = CandidateExperience::findOrFail($this->route($param));
        }

        return $this->candidateExperience;
    }

    public function rules(): array
    {
        return [
            /**
             * From date
             * @example 2020-01-01
             */
            "from_date" => ["required", "date", "before:today"],
            /**
             * To date
             * @example 2022-01-01
             */
            "to_date" => ["required", "date", "before:today"],
            /**
             * Emplpoyer name
             * @example KFC Tech
             */
            "employer_name" => ["required", "string", "max:100"],
            /**
             * Emplpoyer description
             * @example Provide ERP solutions
             */
            "employer_description" => ["string", "max:500"],
            /**
             * Position title
             * @example Software Engineer
             */
            "position_title" => ["required", "string", "max:100"],
            /**
             * Position duty
             * @example Work with PHP and Laravel
             */
            "position_duty" => ["required", "string", "max:500"],
            /**
             * Note
             * @example Need further background check
             */
            "note" => ["string", "max:500"],
        ];
    }
}
