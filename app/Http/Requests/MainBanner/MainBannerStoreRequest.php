<?php

namespace App\Http\Requests\MainBanner;

use App\Http\Requests\Product\BaseFormRequest;
use Illuminate\Validation\Rule;

class MainBannerStoreRequest extends BaseFormRequest
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
            'product_id' => ['required', 'exists:products,id'],
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'image' => ['required', 'image', 'max:10240'],
            'order' => ['integer', 'unique:main_banners']
        ];
    }

    public function messages(): array
    {
        return [
            'product_id.required' => 'Поле "ID продукта" обязательно для заполнения.',
            'product_id.exists' => 'Выбранный продукт не существует.',

            'title.required' => 'Поле "Заголовок" обязательно для заполнения.',
            'title.string' => 'Поле "Заголовок" должно быть строкой.',
            'title.max' => 'Поле "Заголовок" не должно превышать 255 символов.',

            'description.required' => 'Поле "Описание" обязательно для заполнения.',
            'description.string' => 'Поле "Описание" должно быть строкой.',

            'image.required' => 'Поле "Изображение" обязательно для заполнения.',
            'image.image' => 'Поле "Изображение" должно быть изображением.',
            'image.max' => 'Размер файла изображения не должен превышать 10 МБ.',

            'order.integer' => 'Поле "Порядок изобажений" должно быть целым числом.',
            'order.unique' => 'Значение поля "Порядок изобажений" должно быть уникальным в таблице "Основные баннеры".',
        ];
    }
}
