<?php

namespace App\Http\Requests\Option;

use App\Http\Requests\Product\BaseFormRequest;

class OptionStoreRequest extends BaseFormRequest
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
            'name' => ['required', 'string'],
            'values' => ['sometimes', 'array'],
            'values.*.value' => ['required_with:values:', 'string'],
            'values.*.price' => ['required_with:values', 'numeric'],
            'values.*.order' => ['nullable', 'integer', 'min:0', 'max:155'],
            'values.*.from-library' => ['boolean'],
            'values.*.image-library' => ['string'],
            'values.*.image' => ['nullable', 'image', 'mimes:jpg,png,jpeg,gif', 'max:10240'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Поле "Название" обязательно для заполнения.',
            'name.string' => 'Поле "Название" должно быть строкой.',

            'values.sometimes' => 'Поле "Значения" не обязательно, но должно быть массивом при наличии.',
            'values.array' => 'Поле "Значения" должно быть массивом.',

            'values.*.value.required_with' => 'Поле "Значение" обязательно, если указаны другие значения.',
            'values.*.value.string' => 'Поле "Значение" должно быть строкой.',

            'values.*.price.required_with' => 'Поле "Цена" обязательно, если указаны другие значения.',
            'values.*.price.numeric' => 'Поле "Цена" должно быть числовым значением.',

            'values.*.image.nullable' => 'Поле "Изображение" может быть пустым.',
            'values.*.image.image' => 'Поле "Изображение" должно быть изображением.',
            'values.*.image.mimes' => 'Поле "Изображение" должно быть в формате: jpg, png, jpeg, gif.',
            'values.*.image.max' => 'Размер файла изображения не должен превышать 10 МБ.',
        ];
    }

    public function prepareForValidation()
    {
        if (isset($this->values) && is_array($this->values)) {
            $values = array_map(function ($value) {
                if (isset($value['from-library'])) {
                    $value['from-library'] = filter_var($value['from-library'], FILTER_VALIDATE_BOOLEAN);
                }
                return $value;
            }, $this->values);

            $this->merge([
                'values' => $values,
            ]);
        }
    }
}
