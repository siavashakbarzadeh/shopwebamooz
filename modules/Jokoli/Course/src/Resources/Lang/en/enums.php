<?php

use Jokoli\Course\Enums\CourseConfirmationStatus;
use Jokoli\Course\Enums\CourseStatus;
use Jokoli\Course\Enums\CourseType;

return [
    CourseType::class => [
        CourseType::Free => "free",
        CourseType::Cash => "cash",
    ],
    CourseStatus::class => [
        CourseStatus::Completed => "completed",
        CourseStatus::Uncompleted => "uncompleted",
        CourseStatus::Locked => "locked",
    ],
    CourseConfirmationStatus::class => [
        CourseConfirmationStatus::Pending => "pending",
        CourseConfirmationStatus::Accepted => "accepted",
        CourseConfirmationStatus::Rejected => "rejected",
    ],
];
