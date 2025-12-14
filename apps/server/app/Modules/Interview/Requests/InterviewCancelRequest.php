<?php

namespace App\Modules\Interview\Requests;

use App\Modules\Interview\Abstracts\BaseInterviewRequest;
use App\Modules\Interview\Enums\InterviewStatus;
use App\Modules\Interview\Models\Interview;
use App\Modules\Interview\Rules\InterviewStatusTransitionsFromRule;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;

class InterviewCancelRequest extends BaseInterviewRequest
{
    public function rules(): array
    {
        return [
            /**
             * Cancel timestamp
             * @ignoreParam
             */
            "cancelled_at" => ["required", "date"],
            /**
             * Cancelled by user
             * @ignoreParam
             */
            "cancelled_by_user_id" => ["required", "integer:strict"],
            /**
             * Reason for cancellation
             * @example The candidate did not show up
             */
            "cancelled_reason" => ["required", "string", "max:300"],
            /**
             * Status === CANCELLED
             * @ignoreParam
             */
            "status" => [
                "required",
                Rule::enum(InterviewStatus::class)->only(InterviewStatus::CANCELLED),
                new InterviewStatusTransitionsFromRule($this->getQueriedInterviewOrFail()->status),
            ],
        ];
    }

    public function authorize(): bool
    {
        Gate::authorize("cancel", Interview::class);
        return true;
    }

    public function prepareForValidation(): void
    {
        parent::prepareForValidation();

        $this->merge([
            "status" => InterviewStatus::CANCELLED->value,
            "cancelled_by_user_id" => Auth::user()->id,
            "cancelled_at" => Carbon::now(),
        ]);
    }
}
