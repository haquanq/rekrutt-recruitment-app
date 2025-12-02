<?php

namespace App\Modules\Proposal\Rules;

use App\Modules\Proposal\Enums\ProposalStatus;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class ProposalStatusTransitionsFromRule implements ValidationRule
{
    public function __construct(protected ProposalStatus $oldStatus) {}

    public function validate(string $attribute, mixed $newStatus, Closure $fail): void
    {
        $newStatus = ProposalStatus::tryFrom($newStatus);

        if (!$newStatus) {
            $fail("The selected proposal status is invalid.");
            return;
        }

        if ($this->oldStatus === $newStatus) {
            return;
        }

        $isDraft = $this->oldStatus === ProposalStatus::DRAFT;
        $isPending = $this->oldStatus === ProposalStatus::PENDING;
        $isRejectedOrApproved = \in_array($this->oldStatus, [ProposalStatus::APPROVED, ProposalStatus::REJECTED]);

        $changesToDraft = $newStatus === ProposalStatus::DRAFT;
        $changesToPending = $newStatus === ProposalStatus::PENDING;
        $changesToRejectedOrApproved = \in_array($newStatus, [ProposalStatus::APPROVED, ProposalStatus::REJECTED]);

        $message = "Can't change proposal status from {$this->oldStatus->value} to {$newStatus->value}.";

        if ($changesToPending && !$isDraft) {
            $requiredStatus = ProposalStatus::DRAFT->value;
            $fail("$message Required status: $requiredStatus");
            return;
        }

        if ($changesToRejectedOrApproved && !$isPending) {
            $requiredStatus = ProposalStatus::PENDING->value;
            $fail("$message Required status: $requiredStatus");
            return;
        }

        if ($changesToDraft && !$isRejectedOrApproved) {
            $requiredStatus = ProposalStatus::APPROVED->value . "," . ProposalStatus::REJECTED->value;
            $fail("$message Required status: $requiredStatus");
            return;
        }
    }
}
