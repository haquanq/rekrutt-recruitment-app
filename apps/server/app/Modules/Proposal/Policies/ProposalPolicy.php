<?php

namespace App\Modules\Proposal\Policies;

use App\Modules\Auth\Enums\UserRole;
use App\Modules\Auth\Models\User;
use App\Modules\Proposal\Models\Proposal;
use Illuminate\Auth\Access\Response;

class ProposalPolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user): bool
    {
        return true;
    }

    public function create(User $user): Response
    {
        if (!$user->hasRole(UserRole::MANAGER, UserRole::HIRING_MANAGER)) {
            return Response::deny("You are not allowed to create new proposal.");
        }

        return Response::allow();
    }

    public function update(User $user, Proposal $proposal): Response
    {
        if (!$user->hasRole(UserRole::MANAGER, UserRole::HIRING_MANAGER)) {
            return Response::deny("You are not allowed to update any proposal.");
        } elseif ($user->id !== $proposal->created_by_user_id) {
            return Response::deny("You are not the author of this proposal.");
        }

        return Response::allow();
    }

    public function delete(User $user, Proposal $proposal): Response
    {
        if (!$user->hasRole(UserRole::MANAGER, UserRole::HIRING_MANAGER)) {
            return Response::deny("You are not allowed to delete any proposal.");
        } elseif ($user->id !== $proposal->created_by_user_id) {
            return Response::deny("You are not the author of this proposal.");
        }

        return Response::allow();
    }

    public function submit(User $user, Proposal $proposal): Response
    {
        if (!$user->hasRole(UserRole::MANAGER, UserRole::HIRING_MANAGER)) {
            return Response::deny("You are not allowed to submit any proposal.");
        } elseif ($user->id !== $proposal->created_by_user_id) {
            return Response::deny("You are not the author of this proposal.");
        }

        return Response::allow();
    }

    public function reject(User $user): Response
    {
        if (!$user->hasRole(UserRole::EXECUTIVE)) {
            return Response::deny("You are not allowed to reject any proposal.");
        }

        return Response::allow();
    }

    public function approve(User $user): Response
    {
        if (!$user->hasRole(UserRole::EXECUTIVE)) {
            return Response::deny("You are not allowed to approve any proposal.");
        }

        return Response::allow();
    }
}
