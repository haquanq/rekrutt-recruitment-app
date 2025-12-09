<?php

namespace App\Modules\Interview\Requests;

use App\Modules\Interview\Rules\InterviewExistsWithStatusRule;
use App\Modules\Interview\Abstracts\BaseInterviewParticipantRequest;
use App\Modules\Interview\Enums\InterviewStatus;
use App\Modules\Interview\Models\Interview;
use App\Modules\Interview\Models\InterviewParticipant;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Validator;

class InterviewParticipantStoreRequest extends BaseInterviewParticipantRequest
{
    public ?Interview $interview = null;

    public function rules(): array
    {
        return [
            /**
             * Note (role of participant)
             * @example Focus on technical skills
             */
            "note" => ["nullable", "string", "max:300"],
            /**
             * Id of Interview
             * @example 1
             */
            "interview_id" => [
                "required",
                "integer:strict",
                InterviewExistsWithStatusRule::create(InterviewStatus::DRAFT)->withInterview($this->interview),
            ],
            /**
             * Id of User
             * @example 1
             */
            "user_id" => ["required", "integer:strict"],
        ];
    }

    public function withValidator(Validator $validator)
    {
        $validator->after(function (Validator $validator) {
            if (!$this->interview) {
                return;
            }

            if ($this->interview->participants()->where("user_id", $this->input("user_id"))->exists()) {
                $validator->errors()->add("user_id", "User is already scheduled for this interview.");
                return;
            }

            $interviews = Interview::whereHas("participants", function ($query) {
                $query->where("user_id", $this->input("user_id"));
            })
                ->where("status", "=", InterviewStatus::SCHEDULED)
                ->get();

            $scheduleOverlapped = $interviews->some(function (Interview $interview) {
                return ($interview->started_at <= $this->interview->ended_at &&
                    $interview->started_at >= $this->interview->starterd_at) ||
                    ($interview->ended_at <= $this->interview->ended_at &&
                        $interview->ended_at >= $this->interview->starterd_at);
            });

            if ($scheduleOverlapped) {
                $validator
                    ->errors()
                    ->add(
                        "user_id",
                        "Schedule overlapped. User is already scheduled for another interview during this period.",
                    );
            }
        });
    }

    public function authorize(): bool
    {
        Gate::authorize("create", [InterviewParticipant::class, $this->interview]);
        return true;
    }

    public function prepareForValidation(): void
    {
        parent::prepareForValidation();

        $this->interview = Interview::find($this->input("interview_id"));
    }
}
