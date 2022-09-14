<?php

namespace Jokoli\User\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Psr\SimpleCache\InvalidArgumentException;

class VerifyCodeService
{
    private static $min = 100000;
    private static $max = 999999;
    private static $digits = 6;
    private static $prefix = 'verify_code_';

    public static function getRule(): array
    {
        return ['required', 'digits:' . self::$digits];
    }

    public static function generate()
    {
        return rand(self::$min, self::$max);
    }

    public static function store($id, $code,$time)
    {
        try {
            Cache::set(self::$prefix . $id, $code, $time);
        } catch (InvalidArgumentException $e) {
            Log::error($e);
        }
    }

    public static function get($id)
    {
        return Cache::get(self::$prefix . $id);
    }

    public static function has($id)
    {
        return Cache::has(self::$prefix . $id);
    }

    public static function delete($id)
    {
        try {
            Cache::delete(self::$prefix . $id);
        } catch (InvalidArgumentException $e) {
            Log::error($e);
        }
    }

    public static function check($id, $code): bool
    {
        if (self::get($id) != $code) return false;
        self::delete($id);
        return true;
    }
}
