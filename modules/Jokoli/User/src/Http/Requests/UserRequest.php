<?php

namespace Jokoli\User\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Jokoli\User\Enums\UserStatus;
use Jokoli\User\Rules\ValidMobile;
use Jokoli\User\Services\VerifyCodeService;

class UserRequest extends FormRequest
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
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email,' . $this->user],
            'mobile' => ['nullable', 'unique:users,mobile,' . $this->user, new ValidMobile()],
            'username' => ['nullable', 'min:3', 'unique:users,username,' . $this->user],
            'headline' => ['nullable', 'string'],
            'password' => ['nullable'/*, new ValidPassword()*/],
            'status' => ['required', Rule::in(UserStatus::asArray())],
            'role' => ['nullable', 'exists:roles,name'],
            'image' => ['nullable', 'image'],
            'bio' => ['nullable', 'string'],
        ];
    }

    protected function passedValidation()
    {
        $this->merge([
            'password' => $this->filled('password') ? Hash::make($this->password) : null,
            'role' => $this->filled('role') ? [$this->role] : [],
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
            'status' => trans('User::validation.attributes.status'),
            'role' => trans('User::validation.attributes.role'),
            'image' => trans('User::validation.attributes.image'),
            'bio' => trans('User::validation.attributes.bio'),
        ];
    }

}
