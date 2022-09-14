<?php

namespace Jokoli\Course\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Jokoli\Category\Repository\CategoryRepository;
use Jokoli\Common\Responses\AjaxResponses;
use Jokoli\Course\Enums\CourseConfirmationStatus;
use Jokoli\Course\Enums\CourseStatus;
use Jokoli\Course\Enums\CourseType;
use Jokoli\Course\Http\Requests\CourseRequest;
use Jokoli\Course\Models\Course;
use Jokoli\Course\Models\Season;
use Jokoli\Course\Repository\CourseRepository;
use Jokoli\Discount\Repository\DiscountRepository;
use Jokoli\Discount\Services\DiscountService;
use Jokoli\Media\Services\MediaFileService;
use Jokoli\Payment\Gateways\Gateway;
use Jokoli\Payment\Repository\PaymentRepository;
use Jokoli\Payment\Services\PaymentService;
use Jokoli\Permission\Enums\Permissions;
use Jokoli\User\Models\User;
use Jokoli\User\Repository\UserRepository;

class CourseController extends Controller
{
    private CourseRepository $courseRepository;

    public function __construct(CourseRepository $courseRepository)
    {
        $this->courseRepository = $courseRepository;
    }

    public function index()
    {
        $this->authorize('manage', Course::class);
        $courses = $this->courseRepository->paginate();
        return view('Course::index', compact('courses'));
    }

    public function details($course)
    {
        $course = $this->courseRepository->findOfFailById($course);
        $this->authorize('details', $course);
        $lessons = $this->courseRepository->lessons($course);
        return view('Course::details', compact('course', 'lessons'));
    }

    public function create(UserRepository $userRepository, CategoryRepository $categoryRepository)
    {
        $this->authorize('create', Course::class);
        $teachers = $userRepository->getTeachers();
        $categories = $categoryRepository->all();
        return view('Course::create', compact('teachers', 'categories'));
    }

    public function store(CourseRequest $request)
    {
        $this->authorize('create', Course::class);
        $request->merge(['banner_id' => MediaFileService::publicUpload($request->image)->id]);
        $this->courseRepository->store($request);
        return redirect()->route('courses.index');
    }

    public function edit(UserRepository $userRepository, CategoryRepository $categoryRepository, $course)
    {
        $course = $this->courseRepository->findOfFailById($course);
        $this->authorize('edit', $course);
        $teachers = $userRepository->getTeachers();
        $categories = $categoryRepository->all();
        return view('Course::edit', compact('course', 'teachers', 'categories'));
    }

    public function update(CourseRequest $request, $course)
    {
        $course = $this->courseRepository->findOfFailById($course);
        $this->authorize('edit', $course);
        if ($request->hasFile('image')) $request->merge(['banner_id' => MediaFileService::publicUpload($request->image)->id]);
        $this->courseRepository->update($request, $course);
        return redirect()->route('courses.index');
    }

    public function destroy($course)
    {
        $this->authorize('delete', Course::class);
        $course = $this->courseRepository->findOfFailById($course);
        try {
            $this->courseRepository->destroy($course);
            return AjaxResponses::successResponse();
        } catch (\Throwable $e) {
            return AjaxResponses::errorResponse();
        }
    }

    public function accept($course)
    {
        $this->authorize('change_confirmation_status', Course::class);
        $course = $this->courseRepository->findOfFailById($course);
        try {
            $status = CourseConfirmationStatus::Accepted();
            $this->courseRepository->updateConfirmationStatus($course, $status->value);
            return AjaxResponses::successResponse([
                'status' => $status->description,
                'class' => $status->getCssClass(),
            ]);
        } catch (\Throwable $e) {
            return AjaxResponses::errorResponse();
        }
    }

    public function reject($course)
    {
        $this->authorize('change_confirmation_status', Course::class);
        $course = $this->courseRepository->findOfFailById($course);
        try {
            $status = CourseConfirmationStatus::Rejected();
            $this->courseRepository->updateConfirmationStatus($course, $status->value);
            return AjaxResponses::successResponse([
                'status' => $status->description,
                'class' => $status->getCssClass(),
            ]);
        } catch (\Throwable $e) {
            return AjaxResponses::errorResponse();
        }
    }

    public function lock($course)
    {
        $this->authorize('change_confirmation_status', Course::class);
        $course = $this->courseRepository->findOfFailById($course);
        try {
            $status = CourseStatus::Locked();
            $this->courseRepository->updateStatus($course, $status->value);
            return AjaxResponses::successResponse([
                'status' => $status->description,
            ]);
        } catch (\Throwable $e) {
            return AjaxResponses::errorResponse();
        }
    }

    public function buy(Request $request, $course)
    {
        $course = $this->courseRepository->findOfFailByIdWithDiscount($course);
        if (!$this->courseCanBePurchased($course)) {
            errorFeedback("عملیات ناموفق", "این دوره قابل خریداری نیست");
            return redirect()->to($course->path());
        }
        if (!$this->authUserCanPurchaseCourse($course)) {
            errorFeedback("عملیات ناموفق", "شما به این دوره دسترسی دارید");
            return redirect()->to($course->path());
        }
        $price = $course->getFinalPrice();
        $discount = null;
        if ($request->filled('code') && $discount = resolve(DiscountRepository::class)->findValidDiscountByCode($course, $request->code)) {
            $price = DiscountService::calculateFinalPrice($course, $discount);
        }
        if ($price <= 0) {
            $this->courseRepository->addStudentToCourse($course, auth()->id());
            showFeedback("عملیات موفق", "شما با موفقیت در دوره شرکت کردید");
            return redirect()->to($course->path());
        }
        $payment = PaymentService::generate($price, $course, $discount);
        return resolve(Gateway::class)->redirect($payment->invoice_id);
    }

    public function downloadLinks($course)
    {
        $course = $this->courseRepository->findOfFailById($course);
        $this->authorize('access', $course);
        return nl2br(implode(PHP_EOL, $course->downloadLinks()));
    }

    private function courseCanBePurchased(Course $course)
    {
        if ($course->type == CourseType::Free) return false;
        if ($course->status == CourseStatus::Locked) return false;
        if ($course->confirmation_status != CourseConfirmationStatus::Accepted) return false;
        return true;
    }

    private function authUserCanPurchaseCourse(Course $course)
    {
        if (auth()->user()->can('access', $course)) return false;
        return true;
    }
}
