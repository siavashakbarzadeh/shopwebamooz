<?php

namespace Jokoli\Front\Http\Controllers;

use App\Http\Controllers\Controller;
use Hekmatinasser\Notowo\Notowo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Jokoli\Course\Models\Lesson;
use Jokoli\Course\Repository\CourseRepository;
use Jokoli\Course\Repository\LessonRepository;
use Jokoli\User\Repository\UserRepository;

class FrontController extends Controller
{
    private CourseRepository $courseRepository;

    public function __construct(CourseRepository $courseRepository)
    {
        $this->courseRepository = $courseRepository;
    }

    public function index()
    {
        $latestCourses = $this->courseRepository->latestCourses();
        $mostPopularCourses = $this->courseRepository->mostPopularCourses();
        return view('Front::index', compact('latestCourses','mostPopularCourses'));
    }

    public function singleCourse(LessonRepository $lessonRepository,Request $request,$course,$slug=null)
    {
        $course = $this->courseRepository->findOfFailByIdAndSlug($course,$slug);
        $lesson=$lessonRepository->getSingleCourseLesson($course,$request);
        return view('Front::course.show', compact('course','lesson'));
    }

    public function tutor(UserRepository $userRepository,$username)
    {
        $user=$userRepository->findOrFailByUsername($username);
        $courses=$userRepository->getCourses($user);
        return view('Front::tutor', compact('user','courses'));
    }
}
