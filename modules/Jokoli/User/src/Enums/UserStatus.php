<?php

namespace Jokoli\User\Enums;

use BenSampo\Enum\Contracts\LocalizedEnum;
use BenSampo\Enum\Enum;

/**
 * @method static static Active()
 * @method static static Inactive()
 * @method static static Ban()
 */
final class UserStatus extends Enum implements LocalizedEnum
{
    const Active =   0;
    const Inactive =   1;
    const Ban = 2;

    public static function getLocalizationKey(): string
    {
        return 'User::enums.' . self::class;
    }

    public function getCssClass()
    {
        return trans('User::classes.' . self::class . '.' . $this->value);
    }
}
