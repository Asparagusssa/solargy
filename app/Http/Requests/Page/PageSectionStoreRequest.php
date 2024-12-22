<?php

namespace App\Http\Requests\Page;

use Illuminate\Foundation\Http\FormRequest;

class PageSectionStoreRequest extends FormRequest
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
            'sections.*.title' => ['required', 'string', 'max:255'],
            'sections.*.html' => ['required', 'string'],
            'sections.*.image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:10240'],
        ];
    }

    public function messages(): array
    {
        return [
            'id.required' => 'Поле "ID" обязательно для заполнения.',
            'id.exists' => 'Страница с указанным "ID" не существует.',

            'sections.required' => 'Поле "Разделы" обязательно для заполнения.',
            'sections.array' => 'Поле "Разделы" должно быть массивом.',

            'sections.*.title.required' => 'Поле "Заголовок" в разделе обязательно для заполнения.',
            'sections.*.title.string' => 'Поле "Заголовок" должно быть строкой.',
            'sections.*.title.max' => 'Поле "Заголовок" не должно превышать 255 символов.',

            'sections.*.html.required' => 'Поле "HTML" в разделе обязательно для заполнения.',
            'sections.*.html.string' => 'Поле "HTML" должно быть строкой.',

            'sections.*.image.nullable' => 'Поле "Изображение" может быть пустым.',
            'sections.*.image.image' => 'Поле "Изображение" должно быть изображением.',
            'sections.*.image.mimes' => 'Поле "Изображение" должно быть в формате: jpeg, png, jpg, gif.',
            'sections.*.image.max' => 'Размер файла изображения не должен превышать 10 МБ.',
        ];
    }

}
