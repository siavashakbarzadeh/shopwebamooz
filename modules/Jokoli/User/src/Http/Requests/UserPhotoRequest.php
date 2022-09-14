<?php

namespace Jokoli\User\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Jokoli\User\Enums\UserStatus;
use Jokoli\User\Rules\ValidMobile;
use Jokoli\User\Services\VerifyCodeService;

class UserPhotoRequest extends FormRequest
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
            'photo' => ['required', 'image'],
        ];
    }

}
