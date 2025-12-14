<?php

namespace App\Modules\Interview\Requests;

use App\Modules\Interview\Abstracts\BaseInterviewEvaluationRequest;
use Illuminate\Support\Facades\Gate;

class InterviewEvaluationDestroyRequest extends BaseInterviewEvaluationRequest
{
    public function rules(): array
    {
        return [];
    }

    public function authorize(): bool
    {
        Gate::authorize("delete", $this->interviewEvaluation);
        return true;
    }
}
