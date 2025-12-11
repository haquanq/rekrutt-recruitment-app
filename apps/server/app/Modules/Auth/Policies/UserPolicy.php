<?php

namespace App\Modules\Auth\Policies;

use App\Modules\Auth\Enums\UserRole;
use App\Modules\Auth\Models\User;
use Illuminate\Auth\Access\Response;

class UserPolicy
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
        if (!$user->hasRole(UserRole::ADMIN)) {
            return Response::deny("You are not allowed to create new user.");
        }

        return Response::allow();
    }

    public function update(User $user): Response
    {
        if (!$user->hasRole(UserRole::ADMIN)) {
            return Response::deny("You are not allowed to update this user.");
        }

        return Response::allow();
    }

    public function delete(User $user): Response
    {
        if (!$user->hasRole(UserRole::ADMIN)) {
            return Response::deny("You are not allowed to delete this user.");
        }

        return Response::allow();
    }

    public function suspend(User $user): Response
    {
        if (!$user->hasRole(UserRole::ADMIN)) {
            return Response::deny("You are not allowed to suspend this user.");
        }

        return Response::allow();
    }

    public function retire(User $user): Response
    {
        if (!$user->hasRole(UserRole::ADMIN)) {
            return Response::deny("You are not allowed to retire this user.");
        }

        return Response::allow();
    }

    public function reactivate(User $user): Response
    {
        if (!$user->hasRole(UserRole::ADMIN)) {
            return Response::deny("You are not allowed to reactivate this user.");
        }

        return Response::allow();
    }
}
