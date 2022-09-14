<?php

namespace Jokoli\User\Tests\Feature;

use Jokoli\User\Models\User;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class ResetPasswordTest extends TestCase
{
    public function test_user_can_see_reset_password_request_form()
    {
        $this->get(route('password.request'))->assertOk();
    }

    public function test_user_can_see_enter_verify_code_form_by_correct_email()
    {
        $this->create_some_users();
        $this->get(route('password.sendVerifyCodeEmail', ['email' => User::query()->first()->email]))->assertOk();
    }

    public function test_user_can_not_see_enter_verify_code_form_by_wrong_email()
    {
        $this->get(route('password.sendVerifyCodeEmail', ['email' => $this->faker->name]))
            ->assertStatus(302);
    }

    public function test_user_banned_after_6_attempt_to_check_verify_code_reset_password()
    {
        $this->create_some_users();
        $email = User::query()->first()->email;
        for ($i = 1; $i <= 5; $i++) {
            $this->post(route('password.check-verify-code'), [
                'email' => $email,
                'verify_code'=>$this->faker->randomNumber(6)
            ])->assertStatus(Response::HTTP_FOUND)
                ->assertSessionHasErrors(['verify_code']);
        }
        $this->post(route('password.check-verify-code'), [
            'email' => $email,
            'verify_code'=>$this->faker->randomNumber(6)
        ])->assertStatus(Response::HTTP_TOO_MANY_REQUESTS);
    }
}
