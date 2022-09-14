<?php

use Jokoli\Permission\Enums\Permissions;
use Jokoli\Permission\Enums\Roles;

return [
    Roles::class => [
        Roles::SupperAdmin => "مدیرکل",
        Roles::Teacher => "مدرس",
        Roles::Student => "دانشجو",
    ],
    Permissions::class => [
        Permissions::ManageCategories => "مدیریت دسته‌بندی‌ها",
        Permissions::ManagePermissions => "مدیریت نقش‌های کاربری",
        Permissions::Teach => "تدریس",
        Permissions::ManageCourses => "مدیریت دوره‌ها",
        Permissions::ManagePayments => "مدیریت تراکنش‌ها",
        Permissions::ManageSettlements => "مدیریت تسویه حساب ها",
        Permissions::ManageDiscounts => "مدیریت تخفیف ها",
        Permissions::ManageUsers => "مدیریت کاربران",
        Permissions::ManageOwnCourses => "مدیریت دوره‌های خود",
    ],
];
