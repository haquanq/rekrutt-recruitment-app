<?php

namespace App\Modules\Candidate\Rules;

use App\Modules\Candidate\Enums\CandidateStatus;
use App\Modules\Candidate\Models\Candidate;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class CandidateExistsWithStatusRule implements ValidationRule
{
    public function __construct(protected CandidateStatus $requiredStatus, protected ?Candidate $candidate = null) {}

    public function validate(string $attribute, mixed $id, Closure $fail): void
    {
        if (!$this->candidate) {
            $this->candidate = Candidate::find($id);
        }

        if (!$this->candidate) {
            $fail("Candidate does not exist.");
            return;
        }

        if ($this->candidate->status !== $this->requiredStatus) {
            $fail("Candidate must have status of {$this->requiredStatus->value}.");
            return;
        }
    }
}
