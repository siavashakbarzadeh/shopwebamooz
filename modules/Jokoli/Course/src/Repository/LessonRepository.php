<?php

namespace Jokoli\Course\Repository;

use Haruncpi\LaravelIdGenerator\IdGenerator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Jokoli\Course\Enums\LessonConfirmationStatus;
use Jokoli\Course\Models\Lesson;

class LessonRepository
{
    private function query()
    {
        return Lesson::query();
    }

    public function store($course, $request)
    {
        return $course->lessons()->create([
            'user_id' => auth()->id(),
            'season_id' => $request->season_id,
            'media_id' => $request->media_id,
            'title' => $request->title,
            'slug' => $request->filled('slug') ? Str::slug($request->slug) : IdGenerator::generate(['table' => 'lessons', 'field' => 'slug', 'length' => 5, 'prefix' => Str::random(4)]),
            'priority' => $request->filled('priority') ? $request->priority : optional($course->latest_lesson()->where('season_id', $request->season_id)->first())->priority + 1,
            'duration' => $request->duration,
            'is_free' => $request->filled('is_free'),
            'body' => $request->body,
        ]);
    }

    public function update($lesson, $request)
    {
        return $lesson->update([
            'season_id' => $request->season_id,
            'media_id' => $request->filled('media_id') ? $request->media_id : $lesson->media_id,
            'title' => $request->title,
            'slug' => $request->filled('slug') ? Str::slug($request->slug) : IdGenerator::generate(['table' => 'lessons', 'field' => 'slug', 'length' => 5, 'prefix' => Str::random(4)]),
            'priority' => $request->filled('priority') ? $request->priority : optional($lesson->course->latest_lesson()->where('season_id', $request->season_id)->first())->priority + 1,
            'duration' => $request->duration,
            'is_free' => $request->filled('is_free'),
            'body' => $request->body,
        ]);
    }

    public function exists($conditions, $except = null)
    {
        return $this->query()
            ->when($except, function (Builder $builder) use ($except) {
                $builder->where('id', '!=', $except);
            })->where($conditions)
            ->accepted()
            ->exists();
    }

    public function acceptAll($course)
    {
        return $course->lessons()->update(['confirmation_status' => LessonConfirmationStatus::Accepted]);
    }

    public function getSingleCourseLesson($course, Request $request)
    {
        //todo User Has Access to Lesson
        return $course->accepted_lessons()
            ->hasAccess()
            ->when($request->filled('lesson') && $this->exists(['id' => $request->lesson]), function (Builder $builder) use ($request) {
                $builder->where('id', $request->lesson);
            })->first();
    }

    public function changeConfirmationStatusWhereInIds($ids, $status)
    {
        return $this->query()->whereIn('id', $ids)->update(['confirmation_status' => $status]);
    }

    public function destroy($lesson)
    {
        return $lesson->delete();
    }

    public function whereInIds(array $ids)
    {
        return $this->query()->whereIn('id', $ids)->get();
    }

    public function findOfFailById($lesson)
    {
        return $this->query()->findOrFail($lesson);
    }

    public function changeConfirmationStatus($lesson, $status)
    {
        return $lesson->update(['confirmation_status' => $status]);
    }

    public function changeStatus($lesson, $status)
    {
        return $lesson->update(['status' => $status]);
    }

}
