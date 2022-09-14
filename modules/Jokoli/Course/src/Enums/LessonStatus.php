<?php

namespace Jokoli\Course\Enums;

use BenSampo\Enum\Contracts\LocalizedEnum;
use BenSampo\Enum\Enum;

/**
 * @method static static Opened()
 * @method static static Locked()
 */
class LessonStatus extends Enum implements LocalizedEnum
{
    const Opened = 0;
    const Locked = 1;

    public static function getLocalizationKey(): string
    {
        return 'Course::enums.' . static::class;
    }

    public function getCssClass()
    {
        return trans('Course::classes.' . static::class . '.' . $this->value);
    }
}
