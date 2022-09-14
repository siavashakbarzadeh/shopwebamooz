<?php

namespace Jokoli\Discount\Services;

class DiscountService
{
    public static function calculateDiscountPercent($course, $discount)
    {
        return ceil(self::calculateDiscountAmount($course, $discount) / $course->getPrice() * 100);
    }

    public static function calculateFinalPrice($course, $discount)
    {
        $discount_amount = self::calculateDiscountAmount($course, $discount);
        return $course->getPrice() > $discount_amount ? $course->getPrice() - $discount_amount : 0;
    }

    public static function calculateDiscountAmount($course, $discount)
    {
        return (($course->getFinalPrice() / 100) * $discount->percent) + $course->getDiscountAmount();
    }
}
