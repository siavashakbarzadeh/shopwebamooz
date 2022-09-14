<?php

namespace Jokoli\Payment\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use Jokoli\Permission\Enums\Permissions;
use Jokoli\User\Models\User;

class PaymentPolicy
{
    use HandlesAuthorization;

    public function manage(User $user)
    {
        return $user->hasPermissionTo(Permissions::ManagePayments);
    }
}
