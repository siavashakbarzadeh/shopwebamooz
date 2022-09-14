<?php

namespace Jokoli\User\Tests\Feature;

use Tests\TestCase;
use Jokoli\User\Models\User;

class LoginTest extends TestCase
{
    public function test_user_can_login_by_email()
    {
        $this->create_some_users();
        $this->post(route('login'), [
            'email' => User::query()->first()->email,
            'password' => self::PASSWORD,
        ])->assertRedirect(route('home'));
        $this->assertAuthenticated();
    }

    public function test_user_can_login_by_mobile()
    {
        $this->create_some_users();
        $this->post(route('login'), [
            'email' => User::query()->first()->mobile,
            'password' => self::PASSWORD,
        ])->assertRedirect(route('home'));
        $this->assertAuthenticated();
    }
}
