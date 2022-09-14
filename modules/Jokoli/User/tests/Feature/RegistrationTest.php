<?php

namespace Jokoli\User\Tests\Feature;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Cache;
use Jokoli\User\Models\User;
use Jokoli\User\Services\VerifyCodeService;
use Tests\TestCase;
use Ybazli\Faker\Facades\Faker;
use function Symfony\Component\Translation\t;

class RegistrationTest extends TestCase
{
    public function test_user_can_see_register_form()
    {
        $this->get(route('register'))->assertOk();
    }

    public function test_user_can_register()
    {
        $this->register_new_user();
        $this->assertDatabaseCount(User::class, 1);
    }

    public function test_user_have_to_verify()
    {
        $this->register_new_user();
        $this->get(route('home'))->assertRedirect(route('verification.notice'));
    }

    public function test_verified_user_can_see_home()
    {
        $this->register_new_user();
        $this->assertAuthenticated();
        auth()->user()->markEmailAsVerified();
        $this->get(route('home'))->assertOk();
    }

    public function test_user_can_verify_account()
    {
        $this->register_new_user();
        $this->assertAuthenticated();
        $this->post(route('verification.verify'), [
            'verify_code' => VerifyCodeService::get(1),
        ])->assertRedirect(route('home'));
        $this->assertTrue(auth()->user()->hasVerifiedEmail());
        $this->assertNotNull(auth()->user()->email_verified_at);
    }

    private function register_new_user()
    {
        $this->assertDatabaseCount(User::class, 0);
        $password = $this->faker->password(8, 12);
        $this->post(route('register'), [
            'name' => $this->faker->name,
            'email' => $this->faker->unique()->email,
            'mobile' => '0911' . rand(1000000, 9999999),
            'password' => $password,
            'password_confirmation' => $password,
        ]);
        $this->assertDatabaseCount(User::class, 1);
    }

}
