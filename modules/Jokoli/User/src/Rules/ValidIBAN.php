<?php

namespace Jokoli\User\Rules;

use Illuminate\Contracts\Validation\Rule;

class ValidIBAN implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //IR090160000000000639280209
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
        return intval(bcmod($this->getIBAN($value), '97')) == 1;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return trans('User::validation.iban', ['attribute' => trans('User::validation.attributes.iban')]);
    }

    private function getIBAN($iban)
    {
        return substr($iban, 4) . collect(str_split(substr($iban, 0, 4)))->map(function ($value) {
                return is_numeric($value) ? $value : strval(array_get($this->letters(), strtoupper($value)));
            })->implode('');
    }

    private function letters(): array
    {
        return [
            'A' => 10,
            'B' => 11,
            'C' => 12,
            'D' => 13,
            'E' => 14,
            'F' => 15,
            'G' => 16,
            'H' => 17,
            'I' => 18,
            'J' => 19,
            'K' => 20,
            'L' => 21,
            'M' => 22,
            'N' => 23,
            'O' => 24,
            'P' => 25,
            'Q' => 26,
            'R' => 27,
            'S' => 28,
            'T' => 29,
            'U' => 30,
            'V' => 31,
            'W' => 32,
            'X' => 33,
            'Y' => 34,
            'Z' => 35,
        ];
    }

}
