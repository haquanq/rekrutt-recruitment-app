<?php

namespace App\Modules\Interview\Requests;

use App\Modules\Interview\Abstracts\BaseInterviewMethodRequest;
use App\Modules\Interview\Models\InterviewMethod;
use Illuminate\Support\Facades\Gate;

class InterviewMethodStoreRequest extends BaseInterviewMethodRequest
{
    public function authorize(): bool
    {
        Gate::authorize("create", InterviewMethod::class);
        return true;
    }
}
