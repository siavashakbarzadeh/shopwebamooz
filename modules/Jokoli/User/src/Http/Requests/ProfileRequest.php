<?php

namespace Jokoli\User\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Jokoli\Permission\Enums\Permissions;
use Jokoli\User\Enums\UserStatus;
use Jokoli\User\Rules\ValidCardNumber;
use Jokoli\User\Rules\ValidMobile;
use Jokoli\User\Rules\ValidIBAN;
use Jokoli\User\Services\VerifyCodeService;

class ProfileRequest extends FormRequest
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
        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email,' . auth()->id()],
            'mobile' => ['nullable', 'unique:users,mobile,' . auth()->id(), new ValidMobile()],
            'password' => ['nullable'/*, new ValidPassword()*/],
        ];
        if (auth()->user()->hasPermissionTo(Permissions::Teach)) {
            $rules['username'] = ['required', 'min:3', 'unique:users,username,' . auth()->id()];
            $rules['card_number'] = ['required', new ValidCardNumber()];
            $rules['iban'] = ['required', new ValidIBAN()];
            $rules['headline'] = ['required', 'string'];
            $rules['bio'] = ['required', 'string'];
        }
        return $rules;
    }

    protected function passedValidation()
    {
        $this->merge([
            'password' => $this->filled('password') ? Hash::make($this->password) : null,
            'email_verified_at' => auth()->user()->email != $this->email ? null : auth()->user()->email_verified_at,
        ]);
    }

    public function attributes()
    {
        return [
            'name' => trans('User::validation.attributes.name'),
            'email' => trans('User::validation.attributes.email'),
            'mobile' => trans('User::validation.attributes.mobile'),
            'username' => trans('User::validation.attributes.username'),
            'headline' => trans('User::validation.attributes.headline'),
            'password' => trans('User::validation.attributes.password'),
            'bio' => trans('User::validation.attributes.bio'),
            'card_number' => trans('User::validation.attributes.card_number'),
            'iban' => trans('User::validation.attributes.iban'),
        ];
    }

}
