<?php

namespace Jokoli\Permission\Enums;

use BenSampo\Enum\Contracts\LocalizedEnum;
use BenSampo\Enum\Enum;
use Illuminate\Support\Facades\Lang;

class Permissions extends Enum implements LocalizedEnum
{
    const ManageCategories = "manage categories";
    const ManagePermissions = "manage permissions";
    const ManageCourses = "manage courses";
    const ManageUsers = "manage users";
    const ManagePayments = "manage payments";
    const ManageSettlements = "manage settlements";
    const ManageDiscounts = "manage discounts";
    const ManageTickets = "manage tickets";
    const ManageOwnCourses = "manage own courses";
    const Teach = "teach";

    public static function getLocalizationKey(): string
    {
        return 'Permission::enums.' . static::class;
    }

}
