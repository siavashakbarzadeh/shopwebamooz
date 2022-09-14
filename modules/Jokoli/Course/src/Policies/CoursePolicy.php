<?php

namespace Jokoli\Course\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use Jokoli\Course\Models\Course;
use Jokoli\Course\Repository\CourseRepository;
use Jokoli\Permission\Enums\Permissions;
use Jokoli\User\Models\User;

class CoursePolicy
{
    use HandlesAuthorization;

    public function manage(User $user)
    {
        return $user->hasPermissionTo(Permissions::ManageCourses) || $user->hasPermissionTo(Permissions::ManageOwnCourses);
    }

    public function create(User $user)
    {
        return $user->hasPermissionTo(Permissions::ManageCourses) || $user->hasPermissionTo(Permissions::ManageOwnCourses);
    }

    public function edit(User $user, Course $course)
    {
        return $user->hasPermissionTo(Permissions::ManageCourses) || ($user->hasPermissionTo(Permissions::ManageOwnCourses) && $user->id == $course->teacher_id);
    }

    public function delete(User $user)
    {
        return $user->hasPermissionTo(Permissions::ManageCourses);
    }

    public function change_confirmation_status(User $user)
    {
        return $user->hasPermissionTo(Permissions::ManageCourses);
    }

    public function details(User $user, Course $course)
    {
        return $user->hasPermissionTo(Permissions::ManageCourses) || ($user->hasPermissionTo(Permissions::ManageOwnCourses) && $user->id == $course->teacher_id);
    }

    public function createSeason(User $user, Course $course)
    {
        return $user->hasPermissionTo(Permissions::ManageCourses) || ($user->hasPermissionTo(Permissions::ManageOwnCourses) && $user->id == $course->teacher_id);
    }

    public function createLesson(User $user, Course $course)
    {
        return $user->hasPermissionTo(Permissions::ManageCourses) || ($user->hasPermissionTo(Permissions::ManageOwnCourses) && $user->id == $course->teacher_id);
    }

    public function access(User $user, Course $course)
    {
        return $user->hasPermissionTo(Permissions::ManageCourses) || $user->id == $course->teacher_id || $course->hasStudent($user->id);
    }
}
