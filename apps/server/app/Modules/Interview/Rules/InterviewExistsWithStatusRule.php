<?php

namespace App\Modules\Candidate\Rules;

use App\Modules\Interview\Enums\InterviewStatus;
use App\Modules\Interview\Models\Interview;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class InterviewExistsWithStatusRule implements ValidationRule
{
    public function __construct(protected InterviewStatus $requiredStatus, protected ?Interview $interview = null) {}

    public function validate(string $attribute, mixed $id, Closure $fail): void
    {
        if (!$this->interview) {
            $this->interview = Interview::find($id);
        }

        if (!$this->interview) {
            $fail("Interview does not exist.");
            return;
        }

        if ($this->interview->status !== $this->requiredStatus) {
            $fail("Interview must have status: " . $this->requiredStatus->value);
            return;
        }
    }
}
