<?php

namespace App\Modules\Proposal\Policies;

use App\Modules\Auth\Enums\UserRole;
use App\Modules\Auth\Models\User;
use App\Modules\Proposal\Models\ProposalDocument;

class ProposalDocumentPolicy
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
        $isManagers = \in_array($role, [UserRole::MANAGER, UserRole::HIRING_MANAGER]);
        return $isManagers;
    }

    public function update(User $user, ProposalDocument $proposalDocument): bool
    {
        $role = UserRole::tryFrom($user["role"]);
        $isManagers = \in_array($role, [UserRole::MANAGER, UserRole::HIRING_MANAGER]);
        $belongsToCurrentUser = $user->id === $proposalDocument->proposal->created_by_user_id;
        return $isManagers && $belongsToCurrentUser;
    }

    public function delete(User $user, ProposalDocument $proposalDocument): bool
    {
        $role = UserRole::tryFrom($user["role"]);
        $isManagers = \in_array($role, [UserRole::MANAGER, UserRole::HIRING_MANAGER]);
        $belongsToCurrentUser = $user->id === $proposalDocument->proposal->created_by_user_id;
        return $isManagers && $belongsToCurrentUser;
    }
}
