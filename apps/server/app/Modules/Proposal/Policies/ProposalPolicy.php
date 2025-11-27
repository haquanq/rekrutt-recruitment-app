<?php

namespace App\Modules\Proposal\Policies;

use App\Modules\Auth\Enums\UserRole;
use App\Modules\Auth\Models\User;
use App\Modules\Proposal\Models\Proposal;

class ProposalPolicy
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
        echo "adawdawd";
        $role = UserRole::tryFrom($user["role"]);
        $isManagers = \in_array($role, [UserRole::MANAGER, UserRole::HIRING_MANAGER]);
        return $isManagers;
    }

    public function update(User $user): bool
    {
        $role = UserRole::tryFrom($user["role"]);
        return \in_array($role, [UserRole::MANAGER, UserRole::HIRING_MANAGER]);
    }

    public function updateResourse(User $user, Proposal $proposal): bool
    {
        return $user->id === $proposal->created_by_user_id;
    }

    public function delete(User $user, Proposal $proposal): bool
    {
        $role = UserRole::tryFrom($user["role"]);
        $isManagers = \in_array($role, [UserRole::MANAGER, UserRole::HIRING_MANAGER]);
        $belongsToCurrentUser = $user->id === $proposal->created_by_user_id;
        return $isManagers && $belongsToCurrentUser;
    }
}
