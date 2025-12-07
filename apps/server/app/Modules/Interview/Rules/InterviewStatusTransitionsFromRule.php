<?php

namespace App\Modules\Interview\Rules;

use App\Modules\Interview\Enums\InterviewStatus;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class InterviewStatusTransitionsFromRule implements ValidationRule
{
    public function __construct(protected InterviewStatus $oldStatus) {}

    public function validate(string $attribute, mixed $newStatus, Closure $fail): void
    {
        $newStatus = InterviewStatus::tryFrom($newStatus);

        if (!$newStatus) {
            $fail("The selected interview status is invalid.");
            return;
        }

        $transitions = [
            InterviewStatus::DRAFT->value => [InterviewStatus::SCHEDULED],
            InterviewStatus::SCHEDULED->value => [InterviewStatus::CANCELLED, InterviewStatus::IN_PROGRESS],
            InterviewStatus::IN_PROGRESS->value => [InterviewStatus::COMPLETED, InterviewStatus::CANCELLED],
            InterviewStatus::UNDER_EVALUATION->value => [InterviewStatus::COMPLETED],
            InterviewStatus::COMPLETED->value => [],
            InterviewStatus::CANCELLED->value => [],
        ];

        if ($this->oldStatus === $newStatus) {
            return;
        }

        if (!\in_array($newStatus, $transitions[$this->oldStatus->value])) {
            $fail("Can't change interview status from {$this->oldStatus->value} to {$newStatus->value}.");
            return;
        }
    }
}
