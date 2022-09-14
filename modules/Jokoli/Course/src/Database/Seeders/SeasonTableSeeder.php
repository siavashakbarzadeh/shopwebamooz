<?php

namespace Jokoli\Course\Database\Seeders;

use Faker\Factory;
use Illuminate\Database\Seeder;
use Jokoli\Course\Models\Course;

class SeasonTableSeeder extends Seeder
{
    public function run()
    {
        $seasons = [
            "نصب و کانفیگ پروژه و پنل مدیریت",
            "دسته بندی ها",
            "محصولات و برند ها",
            "سایر دسته ها در صفحه اصلی",
            "صفحه محصولات",
            "کاربران",
            "پروفایل کاربران و امکانات و ویژگی های دیجی کالا",
            "سبد خرید و سفارشات",
            "صفحه پرداخت و کد تخفیف و کارت هدیه",
            "درگاه پرداخت و ثبت سفارش",
            "تکمیل بحث سفارشات",
            "سطوح دسترسی ها و مقام ها",
            "سئو و سایت مپ در پروژه",
            "آپلود تصاویر محصولات",
            "نظرات بخش محصولات",
            "نقد و بررسی محصولات",
            "پنل فروشندگان و امکانات پنل",
            "مبحث فیلترینگ محصولات",
            "جستجوی پیشرفته",
            "تکمیل بحث سیستم نوتیفیکیشن و اطلاع رسانی",
            "مباحث مربوط به امنیت و ورود به پنل مدیریت و مدرسین",
            "مقایسه کالا",
            "بخش انبار",
            "تکمیل بخش سفارشات و فاکتورگیری",
            "مباحث مربوط به نمودار ها و تکمیل داشبورد",
            "مباحث تکمیلی سایت دیجی کالا",
            "رفع باگ موارد گزارش شده",
        ];
        foreach (Course::all() as $course) {
            foreach (array_random($seasons, rand(5, 8)) as $key => $season) {
                $course->seasons()->create([
                    'user_id' => 1,
                    'title' => $season,
                    'priority' => $key + 1,
                ]);
            }
        }
    }
}
