<?php

namespace App\Modules\Candidate\Rules;

use App\Modules\Candidate\Enums\CandidateStatus;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class CandidateStatusTransitionsFromRule implements ValidationRule
{
    public function __construct(protected CandidateStatus $oldStatus) {}

    public function validate(string $attribute, mixed $newStatus, Closure $fail): void
    {
        $newStatus = CandidateStatus::tryFrom($newStatus);

        if (!$newStatus) {
            $fail("The selected candidate status is invalid.");
            return;
        }

        if ($this->oldStatus === $newStatus) {
            return;
        }

        $transitions = [
            CandidateStatus::READY->value => [CandidateStatus::APPLYING, CandidateStatus::BLACKLISTED],
            CandidateStatus::APPLYING->value => [
                CandidateStatus::ARCHIVED,
                CandidateStatus::EMPLOYED,
                CandidateStatus::BLACKLISTED,
            ],
            CandidateStatus::ARCHIVED->value => [CandidateStatus::READY, CandidateStatus::BLACKLISTED],
            CandidateStatus::EMPLOYED->value => [CandidateStatus::READY],
            CandidateStatus::BLACKLISTED->value => [CandidateStatus::READY],
        ];

        if (!\in_array($newStatus, $transitions[$this->oldStatus->value])) {
            $fail("Can't change candidate status from {$this->oldStatus->value} to {$newStatus->value}.");
        }
    }
}
