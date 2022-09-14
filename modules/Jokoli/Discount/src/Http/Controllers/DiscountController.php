<?php

namespace Jokoli\Discount\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Jokoli\Common\Responses\AjaxResponses;
use Jokoli\Course\Repository\CourseRepository;
use Jokoli\Discount\Http\Requests\DiscountRequest;
use Jokoli\Discount\Models\Discount;
use Jokoli\Discount\Repository\DiscountRepository;
use Jokoli\Discount\Services\DiscountService;

class DiscountController extends Controller
{
    private DiscountRepository $discountRepository;

    public function __construct(DiscountRepository $discountRepository)
    {
        $this->discountRepository = $discountRepository;
    }

    public function index(CourseRepository $courseRepository)
    {
        $this->authorize('index', Discount::class);
        $discounts = $this->discountRepository->paginate();
        $courses = $courseRepository->getAcceptedCourses();
        return view('Discount::index', compact('discounts', 'courses'));
    }

    public function store(DiscountRequest $request)
    {
        $this->authorize('create', Discount::class);
        $this->discountRepository->store($request);
        showFeedback();
        return redirect()->route('discounts.index');
    }

    public function edit(CourseRepository $courseRepository, $discount)
    {
        $this->authorize('edit', Discount::class);
        $discount = $this->discountRepository->findOrFailById($discount);
        $courses = $courseRepository->getAcceptedCourses();
        return view('Discount::edit', compact('discount', 'courses'));
    }

    public function update(DiscountRequest $request, $discount)
    {
        $discount = $this->discountRepository->findOrFailById($discount);
        $this->discountRepository->update($discount, $request);
        showFeedback();
        return redirect()->route('discounts.index');
    }

    public function destroy($discount)
    {
        $this->authorize('destroy', Discount::class);
        $discount = $this->discountRepository->findOrFailById($discount);
        try {
            $this->discountRepository->destory($discount);
            return AjaxResponses::successResponse();
        } catch (\Throwable $e) {
            return AjaxResponses::errorResponse();
        }
    }

    public function check(CourseRepository $courseRepository, Request $request, $course)
    {
        $request->validate(['code' => ['required', 'string']]);
        $course = $courseRepository->findOfFailByIdWithDiscount($course);
        $discount = $this->discountRepository->findValidDiscountByCode($course,$request->code);
        if (!$discount) return AjaxResponses::notFoundResponse([
            'message' => "کد تخفیف معتبر نمیباشد",
            'discount_percent'=>$course->getDiscountPercent(),
            'discount_amount'=>number_format($course->getDiscountAmount()),
            'final_price'=>number_format($course->getFinalPrice()),
        ]);
        return AjaxResponses::successResponse([
            'message' => "کد تخفیف با موفقیت اعمال شد",
            'discount_percent'=>DiscountService::calculateDiscountPercent($course,$discount),
            'discount_amount'=>number_format(DiscountService::calculateDiscountAmount($course,$discount)),
            'final_price'=>number_format(DiscountService::calculateFinalPrice($course,$discount)),
        ]);
    }
}
