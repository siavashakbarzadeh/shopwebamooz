<?php

namespace Jokoli\User\Tests\Unit;

use Jokoli\User\Rules\ValidMobile;
use PHPUnit\Framework\TestCase;

class MobileValidationTest extends TestCase
{
    public function test_mobile_can_not_be_less_than_11_character()
    {
        $this->assertNotTrue((new ValidMobile())->passes('','0911505924'));
    }

    public function test_mobile_can_not_be_greater_than_11_character()
    {
        $this->assertNotTrue((new ValidMobile())->passes('','091150592425'));
    }

    public function test_mobile_should_start_by_09_character()
    {
        $this->assertNotTrue((new ValidMobile())->passes('','5911505924'));
    }
}
