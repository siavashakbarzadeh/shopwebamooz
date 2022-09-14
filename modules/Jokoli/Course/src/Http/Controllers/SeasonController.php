<?php

namespace Jokoli\Course\Http\Controllers;

use App\Http\Controllers\Controller;
use Jokoli\Common\Responses\AjaxResponses;
use Jokoli\Course\Enums\SeasonConfirmationStatus;
use Jokoli\Course\Enums\SeasonStatus;
use Jokoli\Course\Http\Requests\SeasonRequest;
use Jokoli\Course\Models\Season;
use Jokoli\Course\Repository\CourseRepository;
use Jokoli\Course\Repository\SeasonRepository;

class SeasonController extends Controller
{
    private SeasonRepository $seasonRepository;

    public function __construct(SeasonRepository $seasonRepository)
    {
        $this->seasonRepository = $seasonRepository;
    }

    public function store(SeasonRequest $request, CourseRepository $courseRepository, $course)
    {
        $course = $courseRepository->findOfFailById($course);
        $this->authorize('createSeason', $course);
        $this->seasonRepository->store($course, $request);
        showFeedback();
        return redirect()->route('courses.details', $course->id);
    }

    public function edit($season)
    {
        $season = $this->seasonRepository->findOfFailById($season);
        $this->authorize('edit', $season);
        return view('Course::season.edit', compact('season'));
    }

    public function update(SeasonRequest $request, $season)
    {
        $season = $this->seasonRepository->findOfFailById($season);
        $this->authorize('edit', $season);
        $this->seasonRepository->update($season, $request);
        showFeedback();
        return redirect()->route('courses.details', $season->course_id);
    }

    public function destroy($season)
    {
        $season = $this->seasonRepository->findOfFailById($season);
        $this->authorize('delete', $season);
        try {
            $this->seasonRepository->delete($season);
            return AjaxResponses::successResponse();
        } catch (\Throwable $e) {
            return AjaxResponses::errorResponse();
        }
    }

    public function reject($season)
    {
        $this->authorize('changeConfirmationStatus', Season::class);
        $season = $this->seasonRepository->findOfFailById($season);
        return $this->changeConfirmationStatus($season, SeasonConfirmationStatus::Rejected());
    }

    public function accept($season)
    {
        $this->authorize('changeConfirmationStatus', Season::class);
        $season = $this->seasonRepository->findOfFailById($season);
        return $this->changeConfirmationStatus($season, SeasonConfirmationStatus::Accepted());
    }

    public function lock($season)
    {
        $this->authorize('changeStatus', Season::class);
        $season = $this->seasonRepository->findOfFailById($season);
        return $this->changeStatus($season, SeasonStatus::Locked());
    }

    public function unlock($season)
    {
        $this->authorize('changeStatus', Season::class);
        $season = $this->seasonRepository->findOfFailById($season);
        return $this->changeStatus($season, SeasonStatus::Opened());
    }

    private function changeConfirmationStatus($season, $status)
    {
        try {
            $this->seasonRepository->changeConfirmationStatus($season, $status->value);
            return AjaxResponses::successResponse([
                'status' => $status->description,
                'class' => $status->getCssClass(),
            ]);
        } catch (\Throwable $e) {
            return AjaxResponses::errorResponse();
        }
    }

    private function changeStatus($season, $status)
    {
        try {
            $this->seasonRepository->changeStatus($season, $status->value);
            return AjaxResponses::successResponse([
                'status' => $status->description,
                'class' => $status->getCssClass(),
            ]);
        } catch (\Throwable $e) {
            return AjaxResponses::errorResponse();
        }
    }

}
