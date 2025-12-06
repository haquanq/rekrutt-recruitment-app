<?php

namespace App\Modules\Interview\Requests;

use App\Modules\Interview\Abstracts\BaseInterviewRequest;
use App\Modules\Interview\Models\Interview;
use Illuminate\Support\Facades\Gate;

class InterviewUpdateRequest extends BaseInterviewRequest
{
    public Interview $interview;

    public function authorize(): bool
    {
        Gate::authorize("update", $this->interview);
        return true;
    }

    public function prepareForValidation()
    {
        parent::prepareForValidation();

        $this->interview = Interview::findOrFail($this->route("id"));
    }
}
