<?php

namespace App\Http\Requests\SubBanner;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SubBannerUpdateRequest extends FormRequest
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
            'image' => ['image', 'nullable', 'max:10240'],
            'order' => ['integer',
                Rule::unique('sub_banners')->ignore($this->sub_banner->id)
            ]
        ];
    }

    public function messages(): array
    {
        return [
            'title.string' => 'Поле "Заголовок" должно быть строкой.',
            'title.max' => 'Поле "Заголовок" не должно превышать 255 символов.',

            'image.image' => 'Поле "Изображение" должно быть файлом изображения.',
            'image.nullable' => 'Поле "Изображение" может быть пустым.',
            'image.max' => 'Размер изображения не должен превышать 10 МБ.',

            'order.integer' => 'Поле "Порядок изображений" должно быть числом.',
            'order.unique' => 'Поле "Порядок изображений" должно быть уникальным.',
        ];
    }
}
