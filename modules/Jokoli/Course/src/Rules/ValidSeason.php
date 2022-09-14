<?php

namespace Jokoli\Course\Rules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Str;
use Jokoli\Course\Enums\SeasonConfirmationStatus;
use Jokoli\Course\Repository\LessonRepository;
use Jokoli\Course\Repository\SeasonRepository;
use Jokoli\Permission\Enums\Permissions;
use Jokoli\User\Repository\UserRepository;

class ValidSeason implements Rule
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
        return resolve(SeasonRepository::class)->exists(['id' => $value, 'confirmation_status' => SeasonConfirmationStatus::Accepted]);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return trans('validation.exists', ['attribute' => trans('Course::validation.attributes.season_id')]);
    }
}
