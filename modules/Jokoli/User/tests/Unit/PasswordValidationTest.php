<?php

namespace Jokoli\User\Tests\Unit;

use Jokoli\User\Rules\ValidPassword;
use PHPUnit\Framework\TestCase;

class PasswordValidationTest extends TestCase
{
    public function test_password_should_not_less_than_6_character()
    {
        $this->assertNotTrue((new ValidPassword())->passes('','Pa!@$'));
    }

    public function test_password_should_include_sign_character()
    {
        $this->assertNotTrue((new ValidPassword())->passes('','Pass123'));
    }

    public function test_password_should_include_digit_character()
    {
        $this->assertNotTrue((new ValidPassword())->passes('','Pass!@#'));
    }

    public function test_password_should_include_capital_character()
    {
        $this->assertNotTrue((new ValidPassword())->passes('','pass123!@#'));
    }

    public function test_password_should_include_small_character()
    {
        $this->assertNotTrue((new ValidPassword())->passes('','PASS123!@#'));
    }
}
