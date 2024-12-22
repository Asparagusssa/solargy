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
            'file' => ['sometimes', 'nullable', 'file', 'max:10240'],
        ];
    }

    public function messages(): array
    {
        return [
            'title.sometimes' => 'Поле "Заголовок" не обязательно, но если указано, оно должно быть строкой.',
            'title.string' => 'Поле "Заголовок" должно быть строкой.',

            'file.sometimes' => 'Поле "Файл" не обязательно, но если указано, оно должно быть файлом.',
            'file.nullable' => 'Поле "Файл" может быть пустым.',
            'file.file' => 'Поле "Файл" должно быть файлом.',
            'file.max' => 'Размер файла не должен превышать 10 МБ.',
        ];
    }
}
