<?php

namespace Jokoli\Ticket\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TicketRequest extends FormRequest
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
            'title' => ['required', 'string', 'min:3'],
            'body' => ['required', 'string', 'min:3'],
            'attachment' => ['nullable', 'file','max:10240'],
        ];
    }

    public function attributes()
    {
        return [
            'title' => trans('Ticket::validations.attributes.tickets.title'),
            'body' => trans('Ticket::validations.attributes.tickets.body'),
            'attachment' => trans('Ticket::validations.attributes.tickets.attachment'),
        ];
    }
}
