<?php

namespace App\Http\Requests\MainBanner;

use App\Http\Requests\Product\BaseFormRequest;
use Illuminate\Validation\Rule;

class MainBannerUpdateRequest extends BaseFormRequest
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
            'product_id' => ['sometimes', 'exists:products,id'],
            'title' => ['sometimes', 'string', 'max:255'],
            'description' => ['sometimes', 'string'],
            'image' => ['sometimes', 'nullable', 'image', 'max:10240'],
            'order' => ['integer',
                Rule::unique('main_banners')->ignore($this->main_banner->id)
            ]
        ];
    }

    public function messages(): array
    {
        return [
            'product_id.sometimes' => 'Поле "ID продукта" не обязательно, но должно существовать в таблице продуктов при наличии.',
            'product_id.exists' => 'Выбранный продукт не существует.',

            'title.sometimes' => 'Поле "Заголовок" не обязательно, но должно быть строкой при наличии.',
            'title.string' => 'Поле "Заголовок" должно быть строкой.',
            'title.max' => 'Поле "Заголовок" не должно превышать 255 символов.',

            'description.sometimes' => 'Поле "Описание" не обязательно, но должно быть строкой при наличии.',
            'description.string' => 'Поле "Описание" должно быть строкой.',

            'image.sometimes' => 'Поле "Изображение" не обязательно, но должно быть изображением при наличии.',
            'image.nullable' => 'Поле "Изображение" может быть пустым.',
            'image.image' => 'Поле "Изображение" должно быть изображением.',
            'image.max' => 'Размер файла изображения не должен превышать 10 МБ.',

            'order.integer' => 'Поле "Порядок изображения" должно быть целым числом.',
            'order.unique' => 'Значение поля "Порядок изображения" должно быть уникальным',
        ];
    }
}
