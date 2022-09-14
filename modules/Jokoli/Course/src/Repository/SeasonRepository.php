<?php

namespace Jokoli\Course\Repository;

use Illuminate\Http\Request;
use Jokoli\Course\Models\Season;

class SeasonRepository
{
    private function query()
    {
        return Season::query();
    }

    public function store($course, Request $request)
    {
        return $course->seasons()->create([
            'user_id' => auth()->id(),
            'title' => $request->title,
            'priority' => $request->filled('priority') ? $request->priority : optional($course->latest_season)->priority + 1,
        ]);
    }

    public function update($season, $request)
    {
        return $season->update([
            'title' => $request->title,
            'priority' => $request->filled('priority') ? $request->priority : optional($season->course->latest_season)->priority + 1,
        ]);
    }

    public function delete($season)
    {
        return $season->delete();
    }

    public function findOfFailById($season)
    {
        return $this->query()->findOrFail($season);
    }

    public function changeConfirmationStatus($season, $confirmation_status)
    {
        return $season->update(['confirmation_status' => $confirmation_status]);
    }

    public function changeStatus($season, $status)
    {
        return $season->update(['status' => $status]);
    }

    public function exists($conditions)
    {
        return $this->query()->where($conditions)->exists();
    }

    public function hasSeason($course)
    {
        return $this->query()->where('course_id', $course)->accepted()->exists();
    }
}
