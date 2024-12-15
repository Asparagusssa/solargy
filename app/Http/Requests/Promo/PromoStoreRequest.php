<?php

namespace App\Http\Requests\Promo;

use Illuminate\Foundation\Http\FormRequest;

class PromoStoreRequest extends FormRequest
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
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'image' => ['required', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
            'start' => ['required', 'date', 'before:end'],
            'end' => ['required', 'date', 'after:start'],
            'is_archived' => ['nullable', 'boolean'],
        ];
    }

    public function prepareForValidation()
    {
        $this->merge([
            'is_archived' => filter_var($this->is_archived, FILTER_VALIDATE_BOOLEAN),
        ]);
    }
}
