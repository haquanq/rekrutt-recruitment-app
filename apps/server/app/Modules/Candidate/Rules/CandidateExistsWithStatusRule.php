<?php

namespace App\Modules\Candidate\Rules;

use App\Modules\Candidate\Enums\CandidateStatus;
use App\Modules\Candidate\Models\Candidate;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class CandidateExistsWithStatusRule implements ValidationRule
{
    private ?Candidate $candidate = null;
    private bool $withCandidate = false;

    public function __construct(protected CandidateStatus $requiredStatus) {}

    public static function create(CandidateStatus $requiredStatus): self
    {
        return new self($requiredStatus);
    }

    public function withCandidate(?Candidate $candidate): self
    {
        $this->candidate = $candidate;
        $this->withCandidate = true;
        return $this;
    }

    public function validate(string $attribute, mixed $id, Closure $fail): void
    {
        if (!$this->candidate && !$this->withCandidate) {
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
