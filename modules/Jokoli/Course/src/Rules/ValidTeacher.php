<?php

namespace Jokoli\Course\Rules;

use Illuminate\Contracts\Validation\Rule;
use Jokoli\Permission\Enums\Permissions;
use Jokoli\User\Repository\UserRepository;

class ValidTeacher implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param string $attribute
     * @param mixed $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        return resolve(UserRepository::class)->hasPermission($value,Permissions::Teach);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return trans('Course::validation.valid_teacher');
    }
}
