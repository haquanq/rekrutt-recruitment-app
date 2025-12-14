<?php

namespace App\Modules\Interview\Requests;

use App\Modules\Interview\Abstracts\BaseInterviewRequest;
use App\Modules\Interview\Enums\InterviewStatus;
use App\Modules\Interview\Rules\InterviewStatusTransitionsFromRule;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator;

class InterviewScheduleRequest extends BaseInterviewRequest
{
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
                new InterviewStatusTransitionsFromRule($this->getQueriedInterviewOrFail()->status),
            ],
        ];
    }

    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $validator) {
            if ($this->getQueriedInterviewOrFail()->loadCount("participants")->participants_count === 0) {
                $validator->errors()->add("participants", "Interview must have at least one participant.");
            }
        });
    }

    public function authorize(): bool
    {
        Gate::authorize("schedule", $this->getQueriedInterviewOrFail());
        return true;
    }

    public function prepareForValidation(): void
    {
        parent::prepareForValidation();

        $this->merge([
            "status" => InterviewStatus::SCHEDULED->value,
        ]);
    }
}
