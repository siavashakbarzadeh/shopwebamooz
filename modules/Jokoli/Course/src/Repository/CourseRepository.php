<?php

namespace Jokoli\Course\Repository;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Jokoli\Course\Http\Requests\CourseRequest;
use Jokoli\Course\Models\Course;
use Jokoli\Permission\Enums\Permissions;
use Jokoli\Permission\Enums\Roles;

class CourseRepository
{
    private function query()
    {
        return Course::query();
    }

    public function findOfFailByIdWithDiscount($course)
    {
        return $this->query()
            ->accepted()
            ->withDiscount()
            ->findOrFail($course);
    }

    public function getAcceptedCourses()
    {
        return $this->query()->accepted()->get();
    }

    private function courses()
    {
        return $this->query()
            ->with('teacher')
            ->withDiscount()
            ->duration()
            ->accepted()
            ->limit(8);
    }

    public function latestCourses()
    {
        return $this->courses()->get();
    }

    public function mostPopularCourses()
    {
        return $this->courses()
            ->withCount('students')
            ->orderByDesc('students_count')
            ->get();
    }

    public function bestsellingCourses()
    {
        return $this->courses()->get();
    }

    public function findOfFailById($course, $with = [])
    {
        return $this->query()->when(count($with), function (Builder $builder) use ($with) {
            $builder->with($with);
        })->findOrFail($course);
    }

    public function findOfFailByIdAndSlug($course, $slug = null)
    {
        return $this->query()
            ->with(['teacher'])
            ->withCount('students')
            ->withDiscount()
            ->withSeasons()
            ->withCount('accepted_lessons As lessons_count')
            ->duration()
            ->accepted()
            ->when($slug, function (Builder $builder) use ($slug) {
                $builder->where('slug', $slug);
            })->findOrFail($course);
    }

    public function getCourseSeasons($course)
    {
        return $course->seasons()->accepted()->sort()->get();
    }

    public function paginate()
    {
        return $this->query()
            ->with(['teacher', 'banner'])
            ->withCount('students')
            ->when(!auth()->user()->hasPermissionTo(Permissions::ManagePermissions) && auth()->user()->hasPermissionTo(Permissions::ManageOwnCourses), function (Builder $builder) {
                $builder->where('teacher_id', auth()->id());
            }, function (Builder $builder) {
                $builder->orderByRaw('FIELD(`teacher_id`,?) DESC', [auth()->id()]);
            })->latest()
            ->paginate(5);
    }

    public function store(CourseRequest $request)
    {
        return $this->query()->create([
            'teacher_id' => $request->teacher_id,
            'category_id' => $request->category_id,
            'banner_id' => $request->banner_id,
            'title' => $request->title,
            'slug' => Str::slug($request->slug),
            'priority' => $request->priority,
            'price' => $request->price,
            'percent' => $request->percent,
            'type' => $request->type,
            'status' => $request->status,
            'body' => $request->body,
        ]);
    }

    public function update(CourseRequest $request, $course)
    {
        return $course->update([
            'teacher_id' => $request->teacher_id,
            'category_id' => $request->category_id,
            'banner_id' => $request->banner_id ?? $course->banner_id,
            'title' => $request->title,
            'slug' => Str::slug($request->slug),
            'priority' => $request->priority,
            'price' => $request->price,
            'percent' => $request->percent,
            'type' => $request->type,
            'status' => $request->status,
            'body' => $request->body,
        ]);
    }

    public function destroy($course)
    {
        if ($course->banner) $course->banner->delete();
        return $course->delete();
    }

    public function updateConfirmationStatus($course, $confirmation_status)
    {
        return $course->update(['confirmation_status' => $confirmation_status]);
    }

    public function updateStatus($course, $status)
    {
        return $course->update(['status' => $status]);
    }

    public function lessons($course)
    {
        return $course->lessons()->paginate();
    }

    public function addStudentToCourse($course, $user_id)
    {
        return $course->students()->syncWithoutDetaching($user_id);
    }

    public function hasStudent($course, $user_id)
    {
        return $course->students->contains($user_id);
    }
}
