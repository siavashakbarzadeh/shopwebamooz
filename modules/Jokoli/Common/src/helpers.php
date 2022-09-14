<?php

if (!function_exists('showFeedback')) {
    function showFeedback($title = "عملیات موفق", $body = "ثبت اطلاعات با موفقیت انجام شد", $type = "success")
    {
        session()->flash('feedbacks', collect(session('feedbacks', []))->push(['title' => $title, 'body' => $body, 'type' => $type,]));
    }
}

if (!function_exists('errorFeedback')) {
    function errorFeedback($title = "عملیات ناموفق", $body = "خطا در انجام عملیات", $type = "error")
    {
        session()->flash('feedbacks', collect(session('feedbacks', []))->push(['title' => $title, 'body' => $body, 'type' => $type,]));
    }
}

if (!function_exists('validateJalaliDate')) {
    function validateJalaliDate(string $date,string $format = 'Y/m/d'): bool
    {
        if (!is_string($date)) {
            return false;
        }
        try {
            Verta::parseFormat($format, $date);
        } catch (\Exception $e) {
            return false;
        }
        return true;
    }
}

if (! function_exists('fa_to_en')) {
    /**
     * Convert farsi/arabic digits to english.
     *
     * @param  string  $text
     * @return string
     */
    function fa_to_en($text): string
    {
        $fa_num = ['۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹', '٠', '١', '٢', '٣', '٤', '٥', '٦', '٧', '٨', '٩'];
        $en_num = ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9', '0', '1', '2', '3', '4', '5', '6', '7', '8', '9'];

        return str_replace($fa_num, $en_num, $text);
    }
}
