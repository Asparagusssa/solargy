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
            'sections' => ['required', 'array'],
            'sections.*.id' => ['sometimes', 'exists:page_sections,id'],
            'sections.*.title' => ['sometimes', 'string', 'max:255'],
            'sections.*.html' => ['sometimes', 'string'],
            'sections.*.image' => ['sometimes', 'nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:10240'],
        ];
    }

    public function messages(): array
    {
        return [
            'sections.required' => 'Поле "Разделы" обязательно для заполнения.',
            'sections.array' => 'Поле "Разделы" должно быть массивом.',

            'sections.*.id.sometimes' => 'Поле "ID раздела" не обязательно, но если указано, оно должно существовать.',
            'sections.*.id.exists' => 'Раздел с указанным "ID" не существует.',

            'sections.*.title.sometimes' => 'Поле "Заголовок" в разделе не обязательно, но если указано, оно должно быть строкой.',
            'sections.*.title.string' => 'Поле "Заголовок" должно быть строкой.',
            'sections.*.title.max' => 'Поле "Заголовок" не должно превышать 255 символов.',

            'sections.*.html.sometimes' => 'Поле "HTML" в разделе не обязательно, но если указано, оно должно быть строкой.',
            'sections.*.html.string' => 'Поле "HTML" должно быть строкой.',

            'sections.*.image.sometimes' => 'Поле "Изображение" в разделе не обязательно, но если указано, оно должно быть изображением.',
            'sections.*.image.nullable' => 'Поле "Изображение" может быть пустым.',
            'sections.*.image.image' => 'Поле "Изображение" должно быть изображением.',
            'sections.*.image.mimes' => 'Поле "Изображение" должно быть в формате: jpeg, png, jpg, gif.',
            'sections.*.image.max' => 'Размер файла изображения не должен превышать 10 МБ.',
        ];
    }
}
