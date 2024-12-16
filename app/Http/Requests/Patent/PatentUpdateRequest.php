<?php

namespace App\Http\Requests\Patent;

use Illuminate\Foundation\Http\FormRequest;

class PatentUpdateRequest extends FormRequest
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
            'title' => ['sometimes', 'string'],
            'date' => ['sometimes', 'date'],
            'file' => ['sometimes', 'nullable', 'file', 'max:10240'],
        ];
    }
}
