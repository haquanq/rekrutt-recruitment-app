<?php

namespace App\Modules\RatingScale\Policies;

use App\Modules\Auth\Enums\UserRole;
use App\Modules\Auth\Models\User;
use Illuminate\Auth\Access\Response;

class RatingScalePolicy
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
        if (!$user->hasRole(UserRole::HIRING_MANAGER, UserRole::RECRUITER)) {
            return Response::deny("You are not allowed to create new rating scale.");
        }
        return Response::allow();
    }

    public function update(User $user): Response
    {
        if (!$user->hasRole(UserRole::HIRING_MANAGER, UserRole::RECRUITER)) {
            return Response::deny("You are not allowed to update anu rating scale.");
        }
        return Response::allow();
    }

    public function delete(User $user): Response
    {
        if (!$user->hasRole(UserRole::HIRING_MANAGER, UserRole::RECRUITER)) {
            return Response::deny("You are not allowed to delete any rating scale.");
        }
        return Response::allow();
    }
}
