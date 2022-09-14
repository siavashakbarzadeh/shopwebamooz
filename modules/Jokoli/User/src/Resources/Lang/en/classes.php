<?php

use Jokoli\User\Enums\UserStatus;

return [
    UserStatus::class => [
        UserStatus::Active => "text-success",
        UserStatus::Inactive => "text-error",
        UserStatus::Ban => null,
    ],
];
