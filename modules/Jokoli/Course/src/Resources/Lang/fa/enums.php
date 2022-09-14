<?php

use Jokoli\Course\Enums\CourseConfirmationStatus;
use Jokoli\Course\Enums\CourseStatus;
use Jokoli\Course\Enums\CourseType;
use Jokoli\Course\Enums\LessonConfirmationStatus;
use Jokoli\Course\Enums\LessonStatus;
use Jokoli\Course\Enums\SeasonConfirmationStatus;
use Jokoli\Course\Enums\SeasonStatus;

return [
    CourseType::class => [
        CourseType::Free => "رایگان",
        CourseType::Cash => "نقدی",
    ],
    CourseStatus::class => [
        CourseStatus::Completed => "تکمیل شده",
        CourseStatus::Uncompleted => "در حال برگزاری",
        CourseStatus::Locked => "قفل شده",
    ],
    CourseConfirmationStatus::class => [
        CourseConfirmationStatus::Pending => "در حال بررسی",
        CourseConfirmationStatus::Accepted => "تایید شده",
        CourseConfirmationStatus::Rejected => "رد شده",
    ],
    SeasonStatus::class => [
        SeasonStatus::Opened => "باز",
        SeasonStatus::Locked => "قفل شده",
    ],
    SeasonConfirmationStatus::class=>[
        SeasonConfirmationStatus::Pending => "در حال بررسی",
        SeasonConfirmationStatus::Accepted => "تایید شده",
        SeasonConfirmationStatus::Rejected => "رد شده",
    ],
    LessonConfirmationStatus::class => [
        LessonConfirmationStatus::Pending => "در حال بررسی",
        LessonConfirmationStatus::Accepted => "تایید شده",
        LessonConfirmationStatus::Rejected => "رد شده",
    ],
    LessonStatus::class => [
        LessonStatus::Opened => "باز",
        LessonStatus::Locked => "قفل شده",
    ],
];
