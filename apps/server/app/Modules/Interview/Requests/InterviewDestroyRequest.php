<?php

namespace App\Modules\Interview\Requests;

use App\Modules\Interview\Abstracts\BaseInterviewRequest;
use App\Modules\Interview\Models\Interview;
use Illuminate\Support\Facades\Gate;

class InterviewDestroyRequest extends BaseInterviewRequest
{
    public Interview $interview;

    public function rules(): array
    {
        return [];
    }

    public function authorize(): bool
    {
        Gate::authorize("delete", $this->interview);
        return true;
    }

    public function prepareForValidation()
    {
        parent::prepareForValidation();

        $this->interview = Interview::findOrFail($this->route("id"));
    }
}
