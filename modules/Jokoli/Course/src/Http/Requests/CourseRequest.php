<?php

namespace Jokoli\Course\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Jokoli\Course\Enums\CourseStatus;
use Jokoli\Course\Enums\CourseType;
use Jokoli\Course\Models\Course;
use Jokoli\Course\Rules\ValidTeacher;

class CourseRequest extends FormRequest
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
//        dd($this->all());
        return [
            'title' => ['required', 'string', 'min:3', 'max:255'],
            'slug' => ['required', 'string', 'min:3', 'max:255', 'unique:courses,slug,'.$this->course],
            'priority' => ['nullable', 'numeric'],
            'price' => ['required', 'numeric', 'min:0'],
            'percent' => ['required', 'numeric', 'between:0,100'],
            'teacher_id' => ['required', 'exists:users,id', new ValidTeacher()],
            'type' => ['required', Rule::in(CourseType::getValues())],
            'status' => ['required', Rule::in(CourseStatus::getValues())],
            'category_id' => ['required', 'exists:categories,id'],
            'image' => [$this->isMethod('patch') ? 'nullable' : 'required', 'mimes:jpg,jpeg,png'],
            'body' => ['nullable', 'string'],
        ];
    }

    public function attributes()
    {
        return [
            'title' => trans('Course::validation.attributes.title'),
            'slug' => trans('Course::validation.attributes.slug'),
            'priority' => trans('Course::validation.attributes.priority'),
            'price' => trans('Course::validation.attributes.price'),
            'percent' => trans('Course::validation.attributes.percent'),
            'teacher_id' => trans('Course::validation.attributes.teacher_id'),
            'type' => trans('Course::validation.attributes.type'),
            'status' => trans('Course::validation.attributes.status'),
            'category_id' => trans('Course::validation.attributes.category_id'),
            'image' => trans('Course::validation.attributes.image'),
            'body' => trans('Course::validation.attributes.body'),
        ];
    }
}
