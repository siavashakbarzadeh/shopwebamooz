<?php

namespace Jokoli\Course\Http\Requests;

use Haruncpi\LaravelIdGenerator\IdGenerator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Jokoli\Course\Enums\CourseStatus;
use Jokoli\Course\Enums\CourseType;
use Jokoli\Course\Models\Course;
use Jokoli\Course\Repository\CourseRepository;
use Jokoli\Course\Repository\LessonRepository;
use Jokoli\Course\Repository\SeasonRepository;
use Jokoli\Course\Rules\ValidLessonSlug;
use Jokoli\Course\Rules\ValidSeason;
use Jokoli\Course\Rules\ValidTeacher;
use Jokoli\Media\Services\MediaFileService;

class LessonRequest extends FormRequest
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
            'slug' => ['nullable', new ValidLessonSlug($this->lesson)],
            'season_id' => [Rule::requiredIf(resolve(SeasonRepository::class)->hasSeason($this->course)), new ValidSeason()],
            'priority' => ['nullable', 'numeric', 'min:0'],
            'duration' => ['required', 'numeric', 'min:1'],
            'attachment' => [$this->isMethod('patch') ? 'nullable' : 'required', 'file', 'mimes:' . MediaFileService::getExtensions()],
            'body' => ['nullable', 'string'],
        ];
    }

    public function attributes()
    {
        return [
            'title' => trans('Course::validation.attributes.title'),
            'slug' => trans('Course::validation.attributes.slug'),
            'season_id' => trans('Course::validation.attributes.season_id'),
            'priority' => trans('Course::validation.attributes.priority'),
            'duration' => trans('Course::validation.attributes.duration'),
            'attachment' => trans('Course::validation.attributes.attachment'),
            'body' => trans('Course::validation.attributes.body'),
        ];
    }

}
