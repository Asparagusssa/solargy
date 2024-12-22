<?php

namespace App\Http\Requests\SubBanner;

use Illuminate\Foundation\Http\FormRequest;

class SubBannerStoreRequest extends FormRequest
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
            'title' => ['nullable', 'string', 'max:255'],
            'image' => ['required', 'image', 'max:10240'],
            'order' => ['numeric', 'unique:sub_banners'],
        ];
    }

    public function messages(): array
    {
        return [
            'title.nullable' => 'Поле "Заголовок" может быть пропущено.',
            'title.string' => 'Поле "Заголовок" должно быть строкой.',
            'title.max' => 'Поле "Заголовок" не должно превышать 255 символов.',

            'image.required' => 'Поле "Изображение" обязательно для заполнения.',
            'image.image' => 'Поле "Изображение" должно быть файлом изображения.',
            'image.max' => 'Размер файла изображения не должен превышать 10 МБ.',

            'order.numeric' => 'Поле "Порядок изображений" должно быть числом.',
            'order.unique' => 'Поле "Порядок изображений" должно быть уникальным.',
        ];
    }
}
