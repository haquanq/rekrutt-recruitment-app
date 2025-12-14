<?php

namespace App\Modules\Interview\Requests;

use App\Modules\Interview\Abstracts\BaseInterviewRequest;
use App\Modules\Interview\Enums\InterviewStatus;
use App\Modules\Interview\Models\Interview;
use App\Modules\Interview\Rules\InterviewStatusTransitionsFromRule;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;

class InterviewCompleteRequest extends BaseInterviewRequest
{
    public function rules(): array
    {
        return [
            /**
             * Complete timestamp
             * @ignoreParam
             */
            "completed_at" => ["required", "date"],
            /**
             * Status === COMPLETED
             * @ignoreParam
             */
            "status" => [
                "required",
                Rule::enum(InterviewStatus::class)->only(InterviewStatus::COMPLETED),
                new InterviewStatusTransitionsFromRule($this->getQueriedInterviewOrFail()->status),
            ],
        ];
    }

    public function authorize(): bool
    {
        Gate::authorize("complete", Interview::class);
        return true;
    }

    public function prepareForValidation(): void
    {
        parent::prepareForValidation();

        $this->merge([
            "status" => InterviewStatus::COMPLETED->value,
            "completed_at" => Carbon::now(),
        ]);
    }
}
