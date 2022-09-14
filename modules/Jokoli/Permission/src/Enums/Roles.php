<?php

namespace Jokoli\Permission\Enums;

use BenSampo\Enum\Contracts\LocalizedEnum;
use BenSampo\Enum\Enum;
use Illuminate\Support\Facades\Lang;

/**
 * @method static static SupperAdmin()
 * @method static static Teacher()
 */
class Roles extends Enum implements LocalizedEnum
{
    const SupperAdmin = "supper admin";
    const Teacher = "teacher";
    const Student = "student";

    public static function getLocalizationKey(): string
    {
        return 'Permission::enums.' . static::class;
    }

}
