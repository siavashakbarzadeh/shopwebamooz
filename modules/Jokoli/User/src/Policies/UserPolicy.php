<?php

namespace Jokoli\User\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use Jokoli\Permission\Enums\Permissions;
use Jokoli\User\Models\User;

class UserPolicy
{
    use HandlesAuthorization;

    public function index(User $user)
    {
        return $user->hasPermissionTo(Permissions::ManageUsers);
    }

    public function edit(User $user)
    {
        return $user->hasPermissionTo(Permissions::ManageUsers);
    }

    public function manualVerify(User $user)
    {
        return $user->hasPermissionTo(Permissions::ManageUsers);
    }
}
