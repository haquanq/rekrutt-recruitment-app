<?php

namespace App\Modules\Interview\Policies;

use App\Modules\Auth\Enums\UserRole;
use App\Modules\Auth\Models\User;
use App\Modules\Interview\Models\Interview;
use Illuminate\Auth\Access\Response;

class InterviewPolicy
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
            Response::deny("You are not allowed to create new interview");
        }

        return Response::allow();
    }

    public function update(User $user, Interview $interview): Response
    {
        if ($user->hasRole(UserRole::HIRING_MANAGER, UserRole::RECRUITER)) {
            Response::deny("You are not allowed to update any interview");
        } elseif ($user->id !== $interview->created_by_user_id) {
            Response::deny("You are not the creator of this interview");
        }

        return Response::allow();
    }

    public function delete(User $user, Interview $interview): Response
    {
        if ($user->hasRole(UserRole::HIRING_MANAGER, UserRole::RECRUITER)) {
            Response::deny("You are not allowed to delete any interview");
        } elseif ($user->id !== $interview->created_by_user_id) {
            Response::deny("You are not the creator of this interview");
        }

        return Response::allow();
    }

    public function schedule(User $user, Interview $interview): Response
    {
        if ($user->hasRole(UserRole::HIRING_MANAGER, UserRole::RECRUITER)) {
            Response::deny("You are not allowed to schedule any interview");
        } elseif ($user->id !== $interview->created_by_user_id) {
            Response::deny("You are not the creator of this interview");
        }

        return Response::allow();
    }

    public function cancel(User $user): Response
    {
        if ($user->hasRole(UserRole::HIRING_MANAGER, UserRole::RECRUITER)) {
            Response::deny("You are not allowed to cancel any interview");
        }

        return Response::allow();
    }

    public function complete(User $user): Response
    {
        if ($user->hasRole(UserRole::HIRING_MANAGER, UserRole::RECRUITER)) {
            Response::deny("You are not allowed to complete any interview");
        }

        return Response::allow();
    }
}
