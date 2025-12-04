<?php

namespace App\Modules\Candidate\Policies;

use App\Modules\Auth\Enums\UserRole;
use App\Modules\Auth\Models\User;
use Illuminate\Auth\Access\Response;

class CandidateDocumentPolicy
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
        if (!$user->hasRole(UserRole::RECRUITER, UserRole::HIRING_MANAGER)) {
            return Response::deny("You are not allowed to create new candidate document.");
        }

        return Response::allow();
    }

    public function update(User $user): Response
    {
        if (!$user->hasRole(UserRole::RECRUITER, UserRole::HIRING_MANAGER)) {
            return Response::deny("You are not allowed to update any candidate document.");
        }

        return Response::allow();
    }

    public function delete(User $user): Response
    {
        if (!$user->hasRole(UserRole::RECRUITER, UserRole::HIRING_MANAGER)) {
            return Response::deny("You are not allowed to delete any candidate document.");
        }

        return Response::allow();
    }
}
