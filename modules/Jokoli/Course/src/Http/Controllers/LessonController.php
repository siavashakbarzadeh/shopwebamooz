<?php

namespace Jokoli\Course\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Jokoli\Common\Responses\AjaxResponses;
use Jokoli\Course\Enums\LessonConfirmationStatus;
use Jokoli\Course\Enums\LessonStatus;
use Jokoli\Course\Http\Requests\LessonRequest;
use Jokoli\Course\Models\Lesson;
use Jokoli\Course\Repository\CourseRepository;
use Jokoli\Course\Repository\LessonRepository;
use Jokoli\Media\Services\MediaFileService;

class LessonController extends Controller
{
    private CourseRepository $courseRepository;
    private LessonRepository $lessonRepository;

    public function __construct(CourseRepository $courseRepository, LessonRepository $lessonRepository)
    {
        $this->courseRepository = $courseRepository;
        $this->lessonRepository = $lessonRepository;
    }

    public function create($course)
    {
        $course = $this->courseRepository->findOfFailById($course);
        $this->authorize('createLesson', $course);
        $seasons = $this->courseRepository->getCourseSeasons($course);
        return view('Course::lesson.create', compact('course', 'seasons'));
    }

    public function store(LessonRequest $request, $course)
    {
        $course = $this->courseRepository->findOfFailById($course);
        $this->authorize('createLesson', $course);
        $request->merge(['media_id' => MediaFileService::privateUpload($request->file('attachment'))->id]);
        $this->lessonRepository->store($course, $request);
        showFeedback();
        return redirect()->route('courses.details', $course->id);
    }

    public function edit($lesson)
    {
        $lesson = $this->lessonRepository->findOfFailById($lesson);
        $this->authorize('edit', $lesson);
        $seasons = $this->courseRepository->getCourseSeasons($lesson->course);
        return view('Course::lesson.edit', compact('lesson', 'seasons'));
    }

    public function update(LessonRequest $request, $lesson)
    {
        $lesson = $this->lessonRepository->findOfFailById($lesson);
        $this->authorize('edit', $lesson);
        if ($request->hasFile('attachment')) $request->merge(['media_id' => MediaFileService::privateUpload($request->file('attachment'))->id]);
        $this->lessonRepository->update($lesson, $request);
        showFeedback();
        return redirect()->route('courses.details', $lesson->course_id);
    }

    public function destroy($lesson)
    {
        $lesson = $this->lessonRepository->findOfFailById($lesson);
        $this->authorize('delete', $lesson);
        try {
            $this->lessonRepository->destroy($lesson);
            return AjaxResponses::successResponse();
        } catch (\Throwable $e) {
            return AjaxResponses::errorResponse();
        }
    }

    public function destroyMultiple(Request $request)
    {
        $request->validate([
            'ids' => ['required', 'array', 'min:1'],
            'ids.*' => ['exists:lessons,id']
        ]);
        $lessons = $this->lessonRepository->whereInIds($request->ids);
        foreach ($lessons as $lesson) {
            $this->authorize('delete', $lesson);
            $this->lessonRepository->destroy($lesson);
        }
        showFeedback();
        return redirect()->back();
    }

    public function acceptMultiple(Request $request)
    {
        $this->authorize('manage', Lesson::class);
        $request->validate([
            'ids' => ['required', 'array', 'min:1'],
            'ids.*' => ['exists:lessons,id']
        ]);
        $this->lessonRepository->changeConfirmationStatusWhereInIds($request->ids, LessonConfirmationStatus::Accepted);
        showFeedback();
        return redirect()->back();
    }

    public function rejectMultiple(Request $request)
    {
        $this->authorize('manage', Lesson::class);
        $request->validate([
            'ids' => ['required', 'array', 'min:1'],
            'ids.*' => ['exists:lessons,id']
        ]);
        $this->lessonRepository->changeConfirmationStatusWhereInIds($request->ids, LessonConfirmationStatus::Rejected);
        showFeedback();
        return redirect()->back();
    }

    public function acceptAll($course)
    {
        $this->authorize('manage', Lesson::class);
        $course = $this->courseRepository->findOfFailById($course);
        $this->lessonRepository->acceptAll($course);
        showFeedback();
        return redirect()->back();
    }

    public function accept($lesson)
    {
        $this->authorize('manage', Lesson::class);
        $lesson = $this->lessonRepository->findOfFailById($lesson);
        return $this->changeConfirmationStatus($lesson, LessonConfirmationStatus::Accepted());
    }

    public function reject($lesson)
    {
        $this->authorize('manage', Lesson::class);
        $lesson = $this->lessonRepository->findOfFailById($lesson);
        return $this->changeConfirmationStatus($lesson, LessonConfirmationStatus::Rejected());
    }

    public function lock($lesson)
    {
        $this->authorize('manage', Lesson::class);
        $lesson = $this->lessonRepository->findOfFailById($lesson);
        return $this->changeStatus($lesson, LessonStatus::Locked());
    }

    public function unlock($lesson)
    {
        $this->authorize('manage', Lesson::class);
        $lesson = $this->lessonRepository->findOfFailById($lesson);
        return $this->changeStatus($lesson, LessonStatus::Opened());
    }

    private function changeConfirmationStatus($lesson, $status)
    {
        try {
            $this->lessonRepository->changeConfirmationStatus($lesson, $status->value);
            return AjaxResponses::successResponse([
                'status' => $status->description,
                'class' => $status->getCssClass(),
            ]);
        } catch (\Throwable $e) {
            return AjaxResponses::errorResponse();
        }
    }

    private function changeStatus($lesson, $status)
    {
        try {
            $this->lessonRepository->changeStatus($lesson, $status->value);
            return AjaxResponses::successResponse([
                'status' => $status->description,
                'class' => $status->getCssClass(),
            ]);
        } catch (\Throwable $e) {
            return AjaxResponses::errorResponse();
        }
    }
}
