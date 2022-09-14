<?php

use Jokoli\Course\Enums\CourseConfirmationStatus;
use Jokoli\Course\Enums\LessonConfirmationStatus;
use Jokoli\Course\Enums\LessonStatus;
use Jokoli\Course\Enums\SeasonConfirmationStatus;
use Jokoli\Course\Enums\SeasonStatus;

return [
    CourseConfirmationStatus::class => [
        CourseConfirmationStatus::Pending => "text-warning",
        CourseConfirmationStatus::Accepted => "text-success",
        CourseConfirmationStatus::Rejected => "text-error",
    ],
    SeasonConfirmationStatus::class => [
        SeasonConfirmationStatus::Pending => "text-warning",
        SeasonConfirmationStatus::Accepted => "text-success",
        SeasonConfirmationStatus::Rejected => "text-error",
    ],
    SeasonStatus::class => [
        SeasonStatus::Opened => "text-info",
        SeasonStatus::Locked => "text-gray",
    ],
    LessonConfirmationStatus::class => [
        LessonConfirmationStatus::Pending => "text-warning",
        LessonConfirmationStatus::Accepted => "text-success",
        LessonConfirmationStatus::Rejected => "text-error",
    ],
    LessonStatus::class => [
        LessonStatus::Opened => "text-info",
        LessonStatus::Locked => "text-gray",
    ],
];
