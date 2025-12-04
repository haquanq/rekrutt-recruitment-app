<?php

namespace App\Modules\Interview\Requests;

use App\Modules\Interview\Abstracts\BaseInterviewMethodRequest;
use App\Modules\Interview\Models\InterviewMethod;
use Illuminate\Support\Facades\Gate;

class InterviewMethodDestroyRequest extends BaseInterviewMethodRequest
{
    public InterviewMethod $interviewMethod;

    public function authorize(): bool
    {
        Gate::authorize("delete", InterviewMethod::class);
        return true;
    }

    public function prepareForValidation(): void
    {
        parent::prepareForValidation();

        $this->interviewMethod = InterviewMethod::findOrFail($this->route("id"));
    }
}
