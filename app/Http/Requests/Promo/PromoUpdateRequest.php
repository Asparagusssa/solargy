<?php

namespace App\Http\Requests\Promo;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PromoUpdateRequest extends FormRequest
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
            'title' => ['string', 'max:255', 'sometimes'],
            'description' => ['string', 'sometimes'],
            'image' => ['image', 'nullable', 'mimes:jpeg,png,jpg,gif', 'max:10240', 'sometimes'],
            'start' => ['date', 'sometimes'],
            'end' => ['date', 'sometimes'],
            'is_archived' => ['boolean', 'sometimes'],
        ];
    }

    public function prepareForValidation()
    {
        $this->merge([
            'is_archived' => filter_var($this->is_archived, FILTER_VALIDATE_BOOLEAN),
        ]);
    }
}
