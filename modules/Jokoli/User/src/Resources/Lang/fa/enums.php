<?php

use Jokoli\User\Enums\UserStatus;

return [
    UserStatus::class=>[
        UserStatus::Active=>"فعال",
        UserStatus::Inactive=>"غیرفعال",
        UserStatus::Ban=>"مسدود شده",
    ],
];
