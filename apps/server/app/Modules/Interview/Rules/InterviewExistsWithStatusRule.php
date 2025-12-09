<?php

namespace App\Modules\Interview\Rules;

use App\Modules\Interview\Enums\InterviewStatus;
use App\Modules\Interview\Models\Interview;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class InterviewExistsWithStatusRule implements ValidationRule
{
    private ?Interview $interview = null;
    private bool $withInterview = false;

    public function __construct(protected InterviewStatus $requiredStatus) {}

    public static function create(InterviewStatus $requiredStatus): self
    {
        return new self($requiredStatus);
    }

    public function withInterview(?Interview $interview): self
    {
        $this->interview = $interview;
        $this->withInterview = true;
        return $this;
    }

    public function validate(string $attribute, mixed $id, Closure $fail): void
    {
        if (!$this->interview && !$this->withInterview) {
            $this->interview = Interview::find($id);
        }

        if (!$this->interview) {
            $fail("Interview does not exist.");
            return;
        }

        if ($this->interview->status !== $this->requiredStatus) {
            $fail("Interview must have status of {$this->requiredStatus->value}.");
            return;
        }
    }
}
