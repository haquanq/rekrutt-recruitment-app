<?php

namespace App\Modules\Interview\Requests;

use App\Modules\Interview\Abstracts\BaseInterviewRequest;
use App\Modules\Interview\Enums\InterviewStatus;
use App\Modules\Interview\Models\Interview;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;

class InterviewScheduleRequest extends BaseInterviewRequest
{
    public Interview $interview;

    public function rules(): array
    {
        return [
            /**
             * Status === SCHEDULED
             * @ignoreParam
             */
            "status" => ["required", Rule::enum(InterviewStatus::cases())->only(InterviewStatus::SCHEDULED)],
        ];
    }

    public function authorize(): bool
    {
        Gate::authorize("schedule", $this->interview);
        return true;
    }

    public function prepareForValidation()
    {
        parent::prepareForValidation();

        $this->interview = Interview::findOrFail($this->route("id"));

        $this->merge([
            "status" => InterviewStatus::SCHEDULED->value,
        ]);
    }
}
