<?php

namespace Jokoli\Payment\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Jokoli\Payment\Enums\SettlementStatus;
use Jokoli\Payment\Repository\SettlementRepository;
use Jokoli\User\Rules\ValidCardNumber;

class SettlementRequest extends FormRequest
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
        if ($this->isMethod('patch')) {
            return [
                'from.name' => ['required', 'string'],
                'from.card_number' => ['required', new ValidCardNumber()],
                'to.name' => ['nullable', Rule::requiredIf(SettlementStatus::Settled()->value == $this->status), 'string'],
                'to.card_number' => ['nullable', Rule::requiredIf(SettlementStatus::Settled()->value == $this->status), new ValidCardNumber()],
                'status' => ['required', Rule::in(SettlementStatus::asArray())],
            ];
        }
        return [
            'amount' => ['required', 'numeric', 'min:10000', 'max:' . auth()->user()->balance],
            'from.name' => ['required', 'string'],
            'from.card_number' => ['required', new ValidCardNumber()],
        ];
    }

    protected function prepareForValidation()
    {
        $this->merge(['amount' => $this->filled('amount') ? str_replace(',', '', $this->amount) : null]);
    }

    protected function passedValidation()
    {
        $this->merge(['to' => $this->to && count(array_filter($this->to)) ? $this->to : null]);
    }

    public function attributes()
    {
        return [
            'amount' => trans('Payment::validations.attributes.amount'),
            'status' => trans('Payment::validations.attributes.status'),
            'from.name' => trans('Payment::validations.attributes.from.name'),
            'from.card_number' => trans('Payment::validations.attributes.from.card_number'),
            'to.name' => trans('Payment::validations.attributes.to.name'),
            'to.card_number' => trans('Payment::validations.attributes.to.card_number'),
        ];
    }

    public function messages()
    {
        return [
            'amount.min' => trans('Payment::validations.settlements.amount.min', ['price' => number_format(10000)]),
            'amount.max' => trans('Payment::validations.settlements.amount.max'),
        ];
    }
}
