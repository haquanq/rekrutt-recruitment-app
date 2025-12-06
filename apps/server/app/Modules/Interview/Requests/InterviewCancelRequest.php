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
    public Interview $interview;

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
             * Cancellation reason
             * @example The candidate did not show up
             */
            "cancellation_reason" => ["required", "string", "max:300"],
            /**
             * Status === CANCELLED
             * @ignoreParam
             */
            "status" => [
                "required",
                Rule::enum(InterviewStatus::class)->only(InterviewStatus::CANCELLED),
                new InterviewStatusTransitionsFromRule($this->interview->status),
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

        $this->interview = Interview::findOrFail($this->route("id"));

        $this->merge([
            "status" => InterviewStatus::CANCELLED->value,
            "cancelled_by_user_id" => Auth::user()->id,
            "cancelled_at" => Carbon::now(),
        ]);
    }
}
