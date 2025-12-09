<?php

namespace App\Modules\Recruitment\Rules;

use App\Modules\Recruitment\Enums\RecruitmentApplicationStatus;
use App\Modules\Recruitment\Models\RecruitmentApplication;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class RecruitmentApplicationExistsWithStatusRule implements ValidationRule
{
    private ?RecruitmentApplication $recruitmentApplication = null;
    private bool $withRecruitmentApplication = false;

    public function __construct(protected RecruitmentApplicationStatus $requiredStatus) {}

    public static function create(RecruitmentApplicationStatus $requiredStatus): self
    {
        return new self($requiredStatus);
    }

    public function withRecruitmentApplication(?RecruitmentApplication $recruitmentApplication): self
    {
        $this->withRecruitmentApplication = true;
        $this->recruitmentApplication = $recruitmentApplication;
        return $this;
    }

    public function validate(string $attribute, mixed $id, Closure $fail): void
    {
        if (!$this->recruitmentApplication && !$this->withRecruitmentApplication) {
            $this->recruitmentApplication = RecruitmentApplication::find($id);
        }

        if (!$this->recruitmentApplication) {
            $fail("Recruitment application does not exist.");
            return;
        }

        if ($this->recruitmentApplication->status !== $this->requiredStatus) {
            $fail("Recruitment application must have status of {$this->requiredStatus->value}.");
            return;
        }
    }
}
