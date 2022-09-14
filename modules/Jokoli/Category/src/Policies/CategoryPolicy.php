<?php

namespace Jokoli\Category\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use Jokoli\Permission\Enums\Permissions;
use Jokoli\User\Models\User;

class CategoryPolicy
{
    use HandlesAuthorization;

    public function index(User $user)
    {
        return $user->hasPermissionTo(Permissions::ManageCategories);
    }

    public function create(User $user)
    {
        return $user->hasPermissionTo(Permissions::ManageCategories);
    }

    public function edit(User $user)
    {
        return $user->hasPermissionTo(Permissions::ManageCategories);
    }

    public function destroy(User $user)
    {
        return $user->hasPermissionTo(Permissions::ManageCategories);
    }

}
