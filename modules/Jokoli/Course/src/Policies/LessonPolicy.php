<?php

namespace Jokoli\Course\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use Jokoli\Course\Models\Course;
use Jokoli\Course\Models\Lesson;
use Jokoli\Course\Models\Season;
use Jokoli\Course\Repository\CourseRepository;
use Jokoli\Permission\Enums\Permissions;
use Jokoli\User\Models\User;

class LessonPolicy
{
    use HandlesAuthorization;

    public function manage(User $user)
    {
        return $user->hasPermissionTo(Permissions::ManageCourses);
    }

    public function create(User $user, Lesson $lesson)
    {
        return $user->hasPermissionTo(Permissions::ManageCourses) || ($user->hasPermissionTo(Permissions::ManageOwnCourses) && $user->id == $lesson->course->teacher_id);
    }

    public function edit(User $user, Lesson $lesson)
    {
        return $user->hasPermissionTo(Permissions::ManageCourses) || ($user->hasPermissionTo(Permissions::ManageOwnCourses) && $user->id == $lesson->course->teacher_id);
    }

    public function delete(User $user, Lesson $lesson)
    {
        return $user->hasPermissionTo(Permissions::ManageCourses) || ($user->hasPermissionTo(Permissions::ManageOwnCourses) && $user->id == $lesson->course->teacher_id);
    }

    public function access(User $user, Lesson $lesson)
    {
        return $lesson->is_free || $user->hasPermissionTo(Permissions::ManageCourses) || $user->id == $lesson->course->teacher_id || $lesson->course->hasStudent($user->id);
    }

}
