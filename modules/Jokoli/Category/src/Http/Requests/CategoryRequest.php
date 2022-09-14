<?php

namespace Jokoli\Category\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Jokoli\Category\Models\Category;

class CategoryRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return auth()->user()->can($this->isMethod('patch') ? 'edit' : 'create', Category::class);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'title' => ['required', 'string', 'min:2','unique:categories,title,'.$this->category],
            'slug' => ['required', 'string', 'min:2'],
            'parent_id' => ['nullable', 'exists:categories,id']
        ];
    }
}
