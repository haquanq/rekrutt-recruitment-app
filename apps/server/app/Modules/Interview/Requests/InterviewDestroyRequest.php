<?php

namespace App\Modules\Interview\Requests;

use App\Modules\Interview\Abstracts\BaseInterviewRequest;
use Illuminate\Support\Facades\Gate;

class InterviewDestroyRequest extends BaseInterviewRequest
{
    public function rules(): array
    {
        return [];
    }

    public function authorize(): bool
    {
        Gate::authorize("delete", $this->getAcceptableContentTypes());
        return true;
    }
}
