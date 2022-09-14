<?php

namespace Jokoli\Ticket\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use Jokoli\Permission\Enums\Permissions;
use Jokoli\Ticket\Models\Ticket;
use Jokoli\User\Models\User;

class TicketPolicy
{
    use HandlesAuthorization;

    public function show(User $user, Ticket $ticket)
    {
        return $user->hasPermissionTo(Permissions::ManageTickets) || $user->id == $ticket->user_id;
    }

    public function reply(User $user, Ticket $ticket)
    {
        return $user->hasPermissionTo(Permissions::ManageTickets) || $user->id == $ticket->user_id;
    }

    public function destroy(User $user)
    {
        return $user->hasPermissionTo(Permissions::ManageTickets);
    }

    public function close(User $user,Ticket $ticket)
    {
        return $user->hasPermissionTo(Permissions::ManageTickets) || $user->id == $ticket->user_id;
    }
}
