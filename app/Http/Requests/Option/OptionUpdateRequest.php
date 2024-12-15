<?php

namespace App\Http\Requests\Option;

use App\Http\Requests\Product\BaseFormRequest;

class OptionUpdateRequest extends BaseFormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['string'],
            'values' => ['array'],
            'values.*.id' => ['required_with:values:', 'numeric'],
            'values.*.value' => ['string'],
            'values.*.price' => ['numeric'],
            'values.*.image' => ['image', 'mimes:jpg,png,jpeg,gif', 'max:5120'],
        ];
    }
}
