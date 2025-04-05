<?php

namespace App\Http\Requests\Option;

use App\Http\Requests\Product\BaseFormRequest;

class OptionUpdateRequest extends BaseFormRequest
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
            'name' => ['string'],
            'is_color' => ['nullable', 'boolean'],
            'values' => ['array'],
            'values.*.id' => ['required_with:values:', 'numeric'],
            'values.*.value' => ['string'],
            'values.*.price' => ['numeric'],
            'values.*.order' => ['nullable', 'integer', 'min:0', 'max:155'],
            'values.*.from-library' => ['boolean'],
            'values.*.image-library' => ['string'],
            'values.*.image' => ['image', 'nullable', 'mimes:jpg,png,jpeg,gif', 'max:10240'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.string' => 'Поле "Название" должно быть строкой.',
            'is_color.boolean' => 'Поле "Является ли цветом" должно быть булевым значением.',

            'values.array' => 'Поле "Значения" должно быть массивом.',

            'values.*.id.required_with' => 'Поле "ID" обязательно, если указаны другие значения.',
            'values.*.id.numeric' => 'Поле "ID" должно быть числовым значением.',

            'values.*.value.string' => 'Поле "Значение" должно быть строкой.',

            'values.*.price.numeric' => 'Поле "Цена" должно быть числовым значением.',

            'values.*.image.image' => 'Поле "Изображение" должно быть изображением.',
            'values.*.image.nullable' => 'Поле "Изображение" может быть пустым.',
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

        if (isset($this->is_color)) {
            $is_color = filter_var($this->is_color, FILTER_VALIDATE_BOOLEAN);
            $this->merge([
                'is_color' => $is_color,
            ]);
        }
    }
}
