<?php

namespace App\Http\Requests\Seo;

use Illuminate\Foundation\Http\FormRequest;

class SeoUpdateRequest extends FormRequest
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
            'page_id' => ['sometimes', 'integer', 'exists:pages,id'],
            'name' => ['sometimes', 'string', 'max:255'],
            'content' => ['sometimes', 'string'],
        ];
    }

    public function messages(): array
    {
        return [
            'page_id.sometimes' => 'Поле "ID страницы" может быть пропущено.',
            'page_id.integer' => 'Поле "ID страницы" должно быть целым числом.',
            'page_id.exists' => 'Страница с указанным "ID" не существует.',

            'name.sometimes' => 'Поле "Название" может быть пропущено.',
            'name.string' => 'Поле "Название" должно быть строкой.',
            'name.max' => 'Поле "Название" не должно превышать 255 символов.',

            'content.sometimes' => 'Поле "Контент" может быть пропущено.',
            'content.string' => 'Поле "Контент" должно быть строкой.',
        ];
    }
}
