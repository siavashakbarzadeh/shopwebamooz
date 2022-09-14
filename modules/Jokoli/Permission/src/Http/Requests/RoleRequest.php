<?php

namespace Jokoli\Permission\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RoleRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => ['required', 'string', 'min:2', 'unique:' . config('permission.table_names.roles') . ',name,'.$this->permission],
            'permissions' => ['nullable', 'array'],
            'permissions.*' => ['exists:' . config('permission.table_names.permissions') . ',id'],
        ];
    }

    public function attributes()
    {
        return [
            'permissions' => trans('Permission::validations.attributes.permissions')
        ];
    }

}
