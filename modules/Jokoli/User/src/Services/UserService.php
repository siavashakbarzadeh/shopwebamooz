<?php

namespace Jokoli\User\Services;

use Jokoli\User\Repository\UserRepository;

class UserService
{
    public static function changePassword($user,$password)
    {
        resolve(UserRepository::class)->changePassword($user,$password);
    }
}
