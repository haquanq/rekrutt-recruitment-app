<?php

namespace App\Modules\Proposal\Rules;

use App\Modules\Recruitment\Enums\RecruitmentStatus;
use App\Modules\Recruitment\Models\Recruitment;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class RecruitmentExistsWithStatusRule implements ValidationRule
{
    public function __construct(
        protected RecruitmentStatus $requiredStatus,
        protected ?Recruitment $recruitment = null,
    ) {}

    public function validate(string $attribute, mixed $id, Closure $fail): void
    {
        if (!$this->recruitment) {
            $this->recruitment = Recruitment::find($id);
        }

        if (!$this->recruitment) {
            $fail("Recruitment does not exist");
            return;
        }

        if ($this->recruitment->status !== $this->requiredStatus) {
            $fail("Recruitment must have status: " . $this->requiredStatus->value);
            return;
        }
    }
}
