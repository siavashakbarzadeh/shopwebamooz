<?php

namespace Jokoli\Course\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Jokoli\Course\Enums\CourseStatus;
use Jokoli\Course\Enums\CourseType;
use Jokoli\Course\Models\Course;
use Jokoli\Course\Rules\ValidTeacher;

class SeasonRequest extends FormRequest
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
            'title' => ['required', 'string'],
            'priority' => ['nullable', 'numeric', 'min:0','max:255'],
        ];
    }

    public function attributes()
    {
        return [
            'title' => trans('Course::validation.attributes.title'),
            'priority' => trans('Course::validation.attributes.priority'),
        ];
    }
}
