<?php

namespace App\Modules\Interview\Requests;

use App\Modules\Interview\Abstracts\BaseInterviewRequest;
use App\Modules\Interview\Enums\InterviewStatus;
use App\Modules\Interview\Models\Interview;
use App\Modules\Interview\Rules\InterviewStatusTransitionsFromRule;
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
            "status" => [
                "required",
                Rule::enum(InterviewStatus::class)->only(InterviewStatus::SCHEDULED),
                new InterviewStatusTransitionsFromRule($this->interview->status),
            ],
        ];
    }

    public function authorize(): bool
    {
        Gate::authorize("schedule", $this->interview);
        return true;
    }

    public function prepareForValidation(): void
    {
        parent::prepareForValidation();

        $this->interview = Interview::findOrFail($this->route("id"));

        $this->merge([
            "status" => InterviewStatus::SCHEDULED->value,
        ]);
    }
}
