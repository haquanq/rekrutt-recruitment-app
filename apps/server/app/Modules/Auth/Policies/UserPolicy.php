<?php

namespace App\Modules\Auth\Policies;

use App\Modules\Auth\Enums\UserRole;
use App\Modules\Auth\Models\User;

class UserPolicy
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
        return true;
    }

    public function update(User $user): bool
    {
        return true;
    }

    public function delete(User $user): bool
    {
        return true;
    }
}
