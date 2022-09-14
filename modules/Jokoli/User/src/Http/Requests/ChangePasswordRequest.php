<?php

namespace Jokoli\User\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Jokoli\User\Services\VerifyCodeService;

class ChangePasswordRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'password' => ['required','confirmed'/*, new ValidPassword()*/],
        ];
    }
}
