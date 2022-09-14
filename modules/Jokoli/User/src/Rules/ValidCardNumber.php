<?php

namespace Jokoli\User\Rules;

use Illuminate\Contracts\Validation\Rule;

class ValidCardNumber implements Rule
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
        return strlen($value) == 16 && ctype_digit($value) && $this->validate($value);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return trans('User::validation.card_number', ['attribute' => trans('User::validation.attributes.card_number')]);
    }


    public function validate($number)
    {
        $digits = collect(str_split($number))->map(function ($value, $key) {
            $sum = intval($value) * (($key % 2) == 0 ? 2 : 1);
            return $sum > 9 ? $sum - 9 : $sum;
        });
        return $digits->sum() % 10 == 0;
    }
}
