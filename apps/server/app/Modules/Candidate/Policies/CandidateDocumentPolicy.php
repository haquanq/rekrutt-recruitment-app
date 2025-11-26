<?php

namespace App\Modules\Candidate\Policies;

use App\Modules\Auth\Enums\UserRole;
use App\Modules\Auth\Models\User;

class CandidateDocumentPolicy
{
    public function findAll(User $user): bool
    {
        return true;
    }

    public function findById(User $user): bool
    {
        return true;
    }

    public function create(User $user): bool
    {
        $role = UserRole::tryFrom($user["role"]);
        return \in_array($role, [UserRole::HIRING_MANAGER, UserRole::RECRUITER]);
    }

    public function update(User $user): bool
    {
        $role = UserRole::tryFrom($user["role"]);
        return \in_array($role, [UserRole::HIRING_MANAGER, UserRole::RECRUITER]);
    }

    public function delete(User $user): bool
    {
        $role = UserRole::tryFrom($user["role"]);
        return \in_array($role, [UserRole::HIRING_MANAGER, UserRole::RECRUITER]);
    }
}
