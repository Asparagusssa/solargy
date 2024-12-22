<?php

namespace App\Http\Requests\PurchasePlace;

use Illuminate\Foundation\Http\FormRequest;

class PurchasePlaceUpdateRequest extends FormRequest
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
            'name' => ['sometimes', 'string', 'max:255'],
            'type' => ['sometimes', 'string', 'in:marketplace,partner,store,retailer'],
            'url' => ['sometimes', 'nullable', 'url'],
            'image' => ['sometimes', 'nullable', 'image', 'mimes:jpeg,png,jpg,gif,svg', 'max:10240'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.sometimes' => 'Поле "Название" может быть пустым.',
            'name.string' => 'Поле "Название" должно быть строкой.',
            'name.max' => 'Поле "Название" не должно превышать 255 символов.',

            'type.sometimes' => 'Поле "Тип" может быть пустым.',
            'type.string' => 'Поле "Тип" должно быть строкой.',
            'type.in' => 'Поле "Тип" должно быть одним из значений: marketplace, partner, store, retailer.',

            'url.sometimes' => 'Поле "URL" может быть пустым.',
            'url.nullable' => 'Поле "URL" может быть пустым.',
            'url.url' => 'Поле "URL" должно быть действительным URL.',

            'image.sometimes' => 'Поле "Изображение" может быть пустым.',
            'image.nullable' => 'Поле "Изображение" может быть пустым.',
            'image.image' => 'Поле "Изображение" должно быть изображением.',
            'image.mimes' => 'Поле "Изображение" должно быть в формате: jpeg, png, jpg, gif, svg.',
            'image.max' => 'Размер изображения не должен превышать 10 МБ.',
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'url' => $this->url === 'null' ? '' : $this->url,
        ]);
    }
}
