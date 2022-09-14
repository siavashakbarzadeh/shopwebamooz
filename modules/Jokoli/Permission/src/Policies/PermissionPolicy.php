<?php

namespace Jokoli\Permission\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use Jokoli\Permission\Enums\Permissions;
use Jokoli\User\Models\User;

class PermissionPolicy
{
    use HandlesAuthorization;

    public function index(User $user)
    {
        return $user->hasPermissionTo(Permissions::ManagePermissions);
    }

    public function create(User $user)
    {
        return $user->hasPermissionTo(Permissions::ManagePermissions);
    }

    public function edit(User $user)
    {
        return $user->hasPermissionTo(Permissions::ManagePermissions);
    }

    public function delete(User $user)
    {
        return $user->hasPermissionTo(Permissions::ManagePermissions);
    }
}
