<?php

namespace App\Modules\Proposal\Rules;

use App\Modules\Proposal\Enums\ProposalStatus;
use App\Modules\Proposal\Models\Proposal;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Str;

class ProposalExistsWithStatusRule implements ValidationRule
{
    private ?Proposal $proposal = null;
    private bool $withProposal = false;

    public function __construct(protected ProposalStatus $requiredStatus) {}

    public static function create(ProposalStatus $requiredStatus): self
    {
        return new self($requiredStatus);
    }

    public function withProposal(?Proposal $proposal): self
    {
        $this->withProposal = true;
        $this->proposal = $proposal;
        return $this;
    }

    public function validate(string $attribute, mixed $id, Closure $fail): void
    {
        if (!$this->proposal && !$this->withProposal) {
            $this->proposal = Proposal::find($id);
        }

        if (!$this->proposal) {
            $fail("Proposal does not exist.");
            return;
        }

        if ($this->proposal->status !== $this->requiredStatus) {
            $fail("Proposal must have status of {$this->requiredStatus->value}.");
            return;
        }
    }
}
