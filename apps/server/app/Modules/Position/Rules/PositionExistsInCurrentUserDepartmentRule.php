<?php

namespace App\Modules\Position\Rules;

use App\Modules\Position\Models\Position;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\Auth;

class PositionExistsInCurrentUserDepartmentRule implements ValidationRule
{
    public function validate(string $attribute, mixed $id, Closure $fail): void
    {
        $proposal = Position::find($id);

        if (!$proposal) {
            $fail("Position does not exist");
            return;
        }

        $departmentId = Auth::user()->position->department->id;

        if ($proposal->departmentId !== $departmentId) {
            $fail("Position does not belong to current user department");
            return;
        }
    }
}
