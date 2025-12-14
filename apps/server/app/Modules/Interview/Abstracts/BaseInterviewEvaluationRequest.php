<?php

namespace App\Modules\Interview\Abstracts;

use App\Abstracts\BaseFormRequest;
use App\Modules\Interview\Models\InterviewEvaluation;

abstract class BaseInterviewEvaluationRequest extends BaseFormRequest
{
    protected ?InterviewEvaluation $interviewEvaluation = null;

    public function getQueriedInterviewEvaluationOrFail(string $param = "id"): InterviewEvaluation
    {
        if ($this->interviewEvaluation === null) {
            $this->interviewEvaluation = InterviewEvaluation::findOrFail($this->route($param));
        }

        return $this->interviewEvaluation;
    }

    public function rules(): array
    {
        return [
            /**
             * Comment
             * @example Good attitude
             */
            "comment" => ["required", "string", "max:500"],
        ];
    }
}
