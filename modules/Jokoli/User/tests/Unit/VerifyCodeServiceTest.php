<?php

namespace Jokoli\User\Tests\Unit;

use Illuminate\Support\Facades\Cache;
use Jokoli\User\Services\VerifyCodeService;
use Illuminate\Foundation\Testing\TestCase;
use Tests\CreatesApplication;

class VerifyCodeServiceTest extends TestCase
{
    use CreatesApplication;

    public function test_generated_code_is_6_digits()
    {
        $code = VerifyCodeService::generate();
        $this->assertGreaterThanOrEqual(100000, $code);
        $this->assertLessThanOrEqual(999999, $code);
        $this->assertEquals(6, strlen($code));
        $this->assertIsNumeric($code);
    }

    public function test_store_generated_code_to_cache()
    {
        $code = VerifyCodeService::generate();
        VerifyCodeService::store(1, $code,now()->addDay());
        $this->assertNotNull(VerifyCodeService::get(1));
    }

    public function test_destroy_cache_verification_code()
    {
        $code = VerifyCodeService::generate();
        VerifyCodeService::store(1, $code,now()->addDay());
        VerifyCodeService::delete(1);
        $this->assertNull(VerifyCodeService::get(1));
    }

}
