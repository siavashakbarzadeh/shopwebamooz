<?php

namespace Jokoli\Discount\Http\Requests;

use Hekmatinasser\Verta\Verta;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Jokoli\Course\Enums\CourseConfirmationStatus;
use Jokoli\Discount\Models\Discount;
use Jokoli\Permission\Enums\Permissions;

class DiscountRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return auth()->user()->can($this->isMethod('patch') ? 'edit' : 'create', Discount::class);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'percent' => ['required', 'numeric', 'between:1,100'],
            'code' => ['nullable', 'string', 'min:3', 'unique:discounts,code,' . $this->discount],
            'usage_limitation' => ['nullable', 'numeric', 'min:1'],
            'expire_at' => ['nullable', 'jdatetime:Y/m/d H:i:s', 'jdate_after:' . verta()->format('Y/m/d H:i:s') . ',Y/m/d H:i:s'],
            'courses' => ['nullable', 'array', 'min:1'],
            'courses.*' => ['required', Rule::exists('courses', 'id')->where('confirmation_status', CourseConfirmationStatus::Accepted)],
            'link' => ['nullable', 'url'],
            'description' => ['nullable', 'string'],
        ];
    }

    protected function prepareForValidation()
    {
        $this->merge(['expire_at' => $this->filled('expire_at') ? fa_to_en($this->expire_at) : null]);
    }

    protected function passedValidation()
    {
        $this->merge(['expire_at' => $this->filled('expire_at') ? Verta::parseFormat('Y/m/d H:i:s', $this->expire_at)->datetime() : null]);
    }


}
