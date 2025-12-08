<?php

namespace App\Modules\Recruitment\Rules;

use App\Modules\Recruitment\Enums\RecruitmentApplicationStatus;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class RecruitmentApplicationStatusTransitionsFromRule implements ValidationRule
{
    public function __construct(protected RecruitmentApplicationStatus $oldStatus) {}

    public function validate(string $attribute, mixed $newStatus, Closure $fail): void
    {
        $newStatus = RecruitmentApplicationStatus::tryFrom($newStatus);

        if (!$newStatus) {
            $fail("The selected recruitment application status is invalid.");
            return;
        }

        $transitions = [
            RecruitmentApplicationStatus::PENDING->value => [
                RecruitmentApplicationStatus::INTERVIEW_PLANNING,
                RecruitmentApplicationStatus::DISCARDED,
            ],
            RecruitmentApplicationStatus::INTERVIEW_PLANNING->value => [
                RecruitmentApplicationStatus::INTERVIEW_SCHEDULED,
                RecruitmentApplicationStatus::DISCARDED,
            ],
            RecruitmentApplicationStatus::INTERVIEW_SCHEDULED->value => [
                RecruitmentApplicationStatus::INTERVIEW_COMPLETED,
                RecruitmentApplicationStatus::DISCARDED,
                RecruitmentApplicationStatus::WITHDRAWN,
            ],
            RecruitmentApplicationStatus::INTERVIEW_COMPLETED->value => [
                RecruitmentApplicationStatus::OFFER_PENDING,
                RecruitmentApplicationStatus::DISCARDED,
                RecruitmentApplicationStatus::WITHDRAWN,
            ],
            RecruitmentApplicationStatus::OFFER_PENDING->value => [
                RecruitmentApplicationStatus::OFFER_ACCEPTED,
                RecruitmentApplicationStatus::OFFER_REJECTED,
            ],
            RecruitmentApplicationStatus::OFFER_ACCEPTED->value => [],
            RecruitmentApplicationStatus::OFFER_REJECTED->value => [],
            RecruitmentApplicationStatus::WITHDRAWN->value => [],
            RecruitmentApplicationStatus::DISCARDED->value => [],
        ];

        if ($this->oldStatus === $newStatus) {
            return;
        }

        if (!\in_array($newStatus, $transitions[$this->oldStatus->value])) {
            $fail("Can't change recruitment application status from {$this->oldStatus->value} to {$newStatus->value}.");
            return;
        }
    }
}
