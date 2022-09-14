<?php

namespace Jokoli\Course\Enums;

use BenSampo\Enum\Contracts\LocalizedEnum;
use BenSampo\Enum\Enum;

/**
 * @method static static Free()
 * @method static static Cash()
 */
class CourseType extends Enum implements LocalizedEnum
{
    const Free = 0;
    const Cash = 1;

    public static function getLocalizationKey(): string
    {
        return 'Course::enums.' . static::class;
    }
}
