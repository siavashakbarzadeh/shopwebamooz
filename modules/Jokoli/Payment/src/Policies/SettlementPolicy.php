<?php

namespace Jokoli\Payment\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use Jokoli\Permission\Enums\Permissions;
use Jokoli\User\Models\User;

class SettlementPolicy
{
    use HandlesAuthorization;

    public function manage(User $user)
    {
        return $user->hasPermissionTo(Permissions::ManageSettlements);
    }

    public function index(User $user)
    {
        return $user->hasPermissionTo(Permissions::Teach) || $user->hasPermissionTo(Permissions::ManageSettlements);
    }

    public function create(User $user)
    {
        return $user->hasPermissionTo(Permissions::Teach);
    }
}
