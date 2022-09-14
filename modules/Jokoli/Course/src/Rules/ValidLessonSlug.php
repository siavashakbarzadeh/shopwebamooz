<?php

namespace Jokoli\Course\Rules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Str;
use Jokoli\Course\Repository\LessonRepository;
use Jokoli\Permission\Enums\Permissions;
use Jokoli\User\Repository\UserRepository;

class ValidLessonSlug implements Rule
{
    /**
     * @var null
     */
    private $lesson;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($lesson=null)
    {
        $this->lesson = $lesson;
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
        return preg_match('/^[A-Za-z][A-Za-z0-9]*/', $value) && !resolve(LessonRepository::class)->exists(['slug' => Str::slug($value)],$this->lesson);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return trans('Course::validation.valid_season_slug',['attribute'=>trans('Course::validation.attributes.slug')]);
    }
}
