<?php

namespace App\Modules\Recruitment\Requests;

use App\Modules\Recruitment\Abstracts\BaseRecruitmentApplicationRequest;
use App\Modules\Recruitment\Enums\RecruitmentApplicationStatus;
use App\Modules\Recruitment\Models\RecruitmentApplication;
use App\Modules\Recruitment\Rules\RecruitmentApplicationStatusTransitionsFromRule;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;

class RecruitmentApplicationUpdateOfferStatusRequest extends BaseRecruitmentApplicationRequest
{
    public RecruitmentApplication $recruitmentApplication;

    public function rules(): array
    {
        return [
            /**
             * Status
             * @example OFFER_PENDING
             */
            "status" => [
                "required",
                Rule::enum(RecruitmentApplicationStatus::class)->only([
                    RecruitmentApplicationStatus::OFFER_PENDING,
                    RecruitmentApplicationStatus::OFFER_ACCEPTED,
                    RecruitmentApplicationStatus::OFFER_REJECTED,
                ]),
                new RecruitmentApplicationStatusTransitionsFromRule($this->recruitmentApplication->status),
            ],
            /**
             * Offer started at timestamp === now
             * @ignoreParam
             */
            "offer_started_at" => [
                Rule::excludeIf($this->input("status") !== RecruitmentApplicationStatus::OFFER_PENDING->value),
                "required",
                "date",
                "date_equals:now",
            ],
            /**
             * Offer responded at timestamp
             * @ignoreParam
             */
            "offer_responded_at" => [
                Rule::excludeIf($this->input("status") === RecruitmentApplicationStatus::OFFER_PENDING->value),
                "required",
                "date",
                "date_equals:now",
            ],
            /**
             * Offer expired at timestamp (include when status is OFFER_PENDING)
             * @example 2001-01-01
             */
            "offer_expired_at" => [
                Rule::excludeIf($this->input("status") !== RecruitmentApplicationStatus::OFFER_PENDING->value),
                "required",
                "date",
                "after:today",
            ],
            /**
             * Reason for offer rejection (include when status is OFFER_REJECTED)
             * @example "Salary too low"
             */
            "offer_rejected_reason" => [
                Rule::excludeIf($this->input("status") !== RecruitmentApplicationStatus::OFFER_REJECTED->value),
                "required",
                "string",
                "max:300",
            ],
        ];
    }

    public function authorize(): bool
    {
        Gate::authorize("updateOfferStatus", RecruitmentApplication::class);
        return true;
    }

    public function prepareForValidation(): void
    {
        parent::prepareForValidation();

        $this->recruitmentApplication = RecruitmentApplication::findOrFail($this->route("id"));

        $this->merge([
            "offer_started_at" => Carbon::now(),
            "offer_responded_at" => Carbon::now(),
        ]);
    }
}
