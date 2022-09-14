<?php

namespace Jokoli\Ticket\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Jokoli\Permission\Enums\Permissions;

class TicketReplyRequest extends FormRequest
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
            'body' => ['required', 'string', 'min:3'],
            'attachment' => ['nullable', 'file','max:10240'],
        ];
    }

    public function attributes()
    {
        return [
            'body' => trans('Ticket::validations.attributes.replies.body'),
            'attachment' => trans('Ticket::validations.attributes.replies.attachment'),
        ];
    }
}
