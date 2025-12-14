<?php

namespace App\Modules\Interview\Requests;

use App\Modules\Interview\Abstracts\BaseInterviewRequest;
use Illuminate\Support\Facades\Gate;

class InterviewUpdateRequest extends BaseInterviewRequest
{
    public function authorize(): bool
    {
        Gate::authorize("update", $this->getQueriedInterviewOrFail());
        return true;
    }
}
