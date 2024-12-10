<?php

namespace App\Http\Requests\Company;

use Illuminate\Foundation\Http\FormRequest;

class CompanyUpdateRequest extends FormRequest
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
            'name' => ['sometimes', 'string', 'max:255'],
            'details' => ['sometimes', 'array'],
            'details.*.name' => ['sometimes', 'string'],
            'details.*.office' => ['sometimes', 'string'],
            'details.*.production' => ['sometimes', 'string'],
            'details.*.email' => ['sometimes', 'email'],
            'details.*.phone' => ['sometimes', 'string'],
            'custom-details' => ['array'],
            'custom-details.*.id' => ['sometimes', 'integer'],
            'custom-details.*.title' => ['required_with:custom-details', 'string'],
            'custom-details.*.value' => ['required_with:custom-details', 'string'],
        ];
    }
}
