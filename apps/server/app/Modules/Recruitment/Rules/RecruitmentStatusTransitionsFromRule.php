<?php

namespace App\Modules\Recruitment\Rules;

use App\Modules\Recruitment\Enums\RecruitmentStatus;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class RecruitmentStatusTransitionsFromRule implements ValidationRule
{
    public function __construct(protected RecruitmentStatus $oldStatus) {}

    public function validate(string $attribute, mixed $newStatus, Closure $fail): void
    {
        $newStatus = RecruitmentStatus::tryFrom($newStatus);

        if (!$newStatus) {
            $fail("The selected recruitment status is invalid.");
            return;
        }

        if ($this->oldStatus === $newStatus) {
            return;
        }

        $isDraft = $this->oldStatus === RecruitmentStatus::DRAFT;
        $isPublished = $this->oldStatus === RecruitmentStatus::PUBLISHED;
        $isScheduled = $this->oldStatus === RecruitmentStatus::SCHEDULED;
        $isClosed = $this->oldStatus === RecruitmentStatus::CLOSED;

        $changesToDraft = $newStatus === RecruitmentStatus::PUBLISHED;
        $changesToScheduled = $newStatus === RecruitmentStatus::SCHEDULED;
        $changesToPublished = $newStatus === RecruitmentStatus::PUBLISHED;
        $changesToClosed = $newStatus === RecruitmentStatus::CLOSED;
        $changesToCompleted = $newStatus === RecruitmentStatus::COMPLETED;

        $failConditions = [
            $changesToScheduled && !$isDraft,
            $changesToPublished && !$isScheduled,
            $changesToClosed && !$isPublished,
            $changesToCompleted && $isClosed,
            $changesToDraft,
        ];

        $message = "Can't change recruitment status from {$this->oldStatus->value} to {$newStatus->value}.";

        if (in_array(true, $failConditions)) {
            $fail($message);
        }
    }
}
