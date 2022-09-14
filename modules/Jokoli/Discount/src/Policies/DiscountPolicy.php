<?php

namespace Jokoli\Discount\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use Jokoli\Permission\Enums\Permissions;
use Jokoli\User\Models\User;

class DiscountPolicy
{
    use HandlesAuthorization;

    public function index(User $user)
    {
        return $user->hasPermissionTo(Permissions::ManageDiscounts);
    }

    public function create(User $user)
    {
        return $user->hasPermissionTo(Permissions::ManageDiscounts);
    }

    public function edit(User $user)
    {
        return $user->hasPermissionTo(Permissions::ManageDiscounts);
    }

    public function destroy(User $user)
    {
        return $user->hasPermissionTo(Permissions::ManageDiscounts);
    }
}
