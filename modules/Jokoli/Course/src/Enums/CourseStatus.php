<?php

namespace Jokoli\Course\Enums;

use BenSampo\Enum\Contracts\LocalizedEnum;
use BenSampo\Enum\Enum;

/**
 * @method static static Completed()
 * @method static static Uncompleted()
 * @method static static Locked()
 */
class CourseStatus extends Enum implements LocalizedEnum
{
    const Completed = 0;
    const Uncompleted = 1;
    const Locked = 2;

    public static function getLocalizationKey(): string
    {
        return 'Course::enums.' . static::class;
    }
}
