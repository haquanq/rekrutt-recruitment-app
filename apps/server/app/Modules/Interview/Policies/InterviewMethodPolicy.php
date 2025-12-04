<?php

namespace App\Modules\Interview\Policies;

use App\Modules\Auth\Enums\UserRole;
use App\Modules\Auth\Models\User;
use Illuminate\Auth\Access\Response;

class InterviewMethodPolicy
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
        if ($user->hasRole(UserRole::HIRING_MANAGER, UserRole::RECRUITER)) {
            Response::deny("You are not allowed to create new interview method");
        }

        return Response::allow();
    }

    public function update(User $user): Response
    {
        if ($user->hasRole(UserRole::HIRING_MANAGER, UserRole::RECRUITER)) {
            Response::deny("You are not allowed to update any interview method");
        }

        return Response::allow();
    }

    public function delete(User $user): Response
    {
        if ($user->hasRole(UserRole::HIRING_MANAGER, UserRole::RECRUITER)) {
            Response::deny("You are not allowed to delete any interview method");
        }

        return Response::allow();
    }
}
