<?php

namespace Jokoli\Course\Enums;

use BenSampo\Enum\Contracts\LocalizedEnum;
use BenSampo\Enum\Enum;

/**
 * @method static static Pending()
 * @method static static Accepted()
 * @method static static Rejected()
 */
class LessonConfirmationStatus extends Enum implements LocalizedEnum
{
    const Pending = 0;
    const Accepted = 1;
    const Rejected = 2;

    public static function getLocalizationKey(): string
    {
        return 'Course::enums.' . static::class;
    }

    public function getCssClass()
    {
        return trans('Course::classes.' . static::class . '.' . $this->value);
    }
}
