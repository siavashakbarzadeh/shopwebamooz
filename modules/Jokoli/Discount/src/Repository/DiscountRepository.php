<?php

namespace Jokoli\Discount\Repository;

use Illuminate\Database\Eloquent\Builder;
use Jokoli\Discount\Models\Discount;

class DiscountRepository
{
    private function query()
    {
        return Discount::query();
    }

    public function findValidDiscountByCode($course, $code)
    {
        return $this->query()
            ->where(function (Builder $builder) use ($course) {
                $builder->whereNotExists(function ($query) {
                    $query->from('discountables')
                        ->whereColumn('discountables.discount_id', 'discounts.id');
                })->orWhereExists(function ($query) use ($course) {
                    $query->from('discountables')
                        ->whereColumn('discountables.discount_id', 'discounts.id')
                        ->where('discountables.discountable_type', $course->getMorphClass())
                        ->where('discountables.discountable_id', $course->id);
                });
            })->where('code', $code)
            ->expireAtAfterNowOrNull()
            ->usageLimitationGreaterThanZeroOrNull()
            ->first();
    }

    public function paginate()
    {
        return $this->query()
            ->withCount('courses')
            ->latest()
            ->paginate();
    }

    public function findOrFailById($discount)
    {
        return $this->query()->findOrFail($discount);
    }

    public function store($request)
    {
        $discount = auth()->user()->discounts()->create([
            'code' => $request->code,
            'percent' => $request->percent,
            'usage_limitation' => $request->usage_limitation,
            'expire_at' => $request->expire_at,
            'link' => $request->link,
            'description' => $request->description,
        ]);
        $discount->courses()->sync($request->get('courses', []));
        return $discount;
    }

    public function update($discount, $request)
    {
        $discount->update([
            'code' => $request->code,
            'percent' => $request->percent,
            'usage_limitation' => $request->usage_limitation,
            'expire_at' => $request->expire_at,
            'link' => $request->link,
            'description' => $request->description,
        ]);
        $discount->courses()->sync($request->get('courses', []));
        return $discount;
    }

    public function destory($discount)
    {
        return $discount->delete();
    }
}
