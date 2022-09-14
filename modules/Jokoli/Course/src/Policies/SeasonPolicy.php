<?php

namespace Jokoli\Course\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use Jokoli\Course\Models\Course;
use Jokoli\Course\Models\Season;
use Jokoli\Permission\Enums\Permissions;
use Jokoli\User\Models\User;

class SeasonPolicy
{
    use HandlesAuthorization;

    public function edit(User $user,Season $season)
    {
        return $user->hasPermissionTo(Permissions::ManageCourses) || ($user->hasPermissionTo(Permissions::ManageOwnCourses) && $user->id == $season->course->teacher_id);
    }

    public function delete(User $user,Season $season)
    {
        return $user->hasPermissionTo(Permissions::ManageCourses) || ($user->hasPermissionTo(Permissions::ManageOwnCourses) && $user->id == $season->course->teacher_id);
    }

    public function changeConfirmationStatus(User $user)
    {
        return $user->hasPermissionTo(Permissions::ManageCourses);
    }

    public function changeStatus(User $user)
    {
        return $user->hasPermissionTo(Permissions::ManageCourses);
    }
}
