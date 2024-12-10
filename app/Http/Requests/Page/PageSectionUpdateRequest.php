<?php

namespace App\Http\Requests\Page;

use Illuminate\Foundation\Http\FormRequest;

class PageSectionUpdateRequest extends FormRequest
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
            'id' => ['required', 'exists:pages,id'],
            'sections' => ['required', 'array'],
            'sections.*.id' => ['sometimes', 'exists:page_sections,id'],
            'sections.*.title' => ['sometimes', 'string', 'max:255'],
            'sections.*.html' => ['sometimes', 'string'],
            'sections.*.image' => ['sometimes', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
        ];
    }
}