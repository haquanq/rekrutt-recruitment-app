<?php

namespace App\Modules\Recruitment\Policies;

use App\Modules\Auth\Enums\UserRole;
use App\Modules\Auth\Models\User;
use App\Modules\Recruitment\Models\Recruitment;
use Illuminate\Auth\Access\Response;

class RecruitmentPolicy
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
            return Response::deny("You are not allowed to create new recruitment.");
        }

        return Response::allow();
    }

    public function update(User $user, Recruitment $recruitment): Response
    {
        if (!$user->hasRole(UserRole::HIRING_MANAGER, UserRole::RECRUITER)) {
            return Response::deny("You are not allowed to update any recruitment.");
        } elseif (!$recruitment->isCreatedBy($user)) {
            return Response::deny("You are not the author of this recruitment.");
        }

        return Response::allow();
    }

    public function delete(User $user, Recruitment $recruitment): Response
    {
        if (!$user->hasRole(UserRole::HIRING_MANAGER, UserRole::RECRUITER)) {
            return Response::deny("You are not allowed to delete any recruitment.");
        } elseif (!$recruitment->isCreatedBy($user)) {
            return Response::deny("You are not the author of this recruitment.");
        }

        return Response::allow();
    }

    public function publish(User $user, Recruitment $recruitment): Response
    {
        if (!$user->hasRole(UserRole::HIRING_MANAGER, UserRole::RECRUITER)) {
            return Response::deny("You are not allowed to publish any recruitment.");
        } elseif (!$recruitment->isCreatedBy($user)) {
            return Response::deny("You are not the author of this recruitment.");
        }

        return Response::allow();
    }

    public function close(User $user, Recruitment $recruitment): Response
    {
        if (!$user->hasRole(UserRole::HIRING_MANAGER, UserRole::RECRUITER)) {
            return Response::deny("You are not allowed to close any recruitment.");
        }

        return Response::allow();
    }
}
