<?php

namespace App\Modules\Proposal\Policies;

use App\Modules\Auth\Enums\UserRole;
use App\Modules\Auth\Models\User;
use App\Modules\Proposal\Enums\ProposalStatus;
use App\Modules\Proposal\Models\Proposal;
use Illuminate\Auth\Access\Response;
use Illuminate\Support\Facades\Log;

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
            return Response::deny("You are not allowed to create proposal");
        }

        return Response::allow();
    }

    public function update(User $user, Proposal $proposal): Response
    {
        Log::info(json_encode($user->department));
        if (!$user->hasRole(UserRole::MANAGER, UserRole::HIRING_MANAGER)) {
            return Response::deny("You are not allowed to update this proposal");
        } elseif ($user->id !== $proposal->created_by_user_id) {
            return Response::deny("You are not the author of this proposal");
        }

        return Response::allow();
    }

    public function delete(User $user, Proposal $proposal): Response
    {
        if (!$user->hasRole(UserRole::MANAGER, UserRole::HIRING_MANAGER)) {
            return Response::deny("You are not allowed to delete this proposal");
        } elseif ($user->id !== $proposal->created_by_user_id) {
            return Response::deny("You are not the author of this proposal");
        }

        return Response::allow();
    }

    public function submit(User $user, Proposal $proposal): Response
    {
        if (!$user->hasRole(UserRole::MANAGER, UserRole::HIRING_MANAGER)) {
            return Response::deny("You are not allowed to submit this proposal");
        } elseif ($user->id !== $proposal->created_by_user_id) {
            return Response::deny("You are not the author of this proposal");
        }

        return Response::allow();
    }
}
