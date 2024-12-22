<?php

namespace App\Http\Requests\Promo;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PromoUpdateRequest extends FormRequest
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
            'description' => ['string', 'sometimes'],
            'image' => ['image', 'nullable', 'mimes:jpeg,png,jpg,gif', 'max:10240', 'sometimes'],
            'start' => ['date', 'sometimes'],
            'end' => ['date', 'sometimes'],
            'is_archived' => ['boolean', 'sometimes'],
        ];
    }

    public function messages(): array
    {
        return [
            'title.string' => 'Поле "Заголовок" должно быть строкой.',
            'title.max' => 'Поле "Заголовок" не должно превышать 255 символов.',

            'description.string' => 'Поле "Описание" должно быть строкой.',

            'image.image' => 'Поле "Изображение" должно быть изображением.',
            'image.nullable' => 'Поле "Изображение" может быть пустым.',
            'image.mimes' => 'Поле "Изображение" должно быть в формате: jpeg, png, jpg, gif.',
            'image.max' => 'Размер файла изображения не должен превышать 10 МБ.',

            'start.date' => 'Поле "Дата начала" должно быть датой.',

            'end.date' => 'Поле "Дата окончания" должно быть датой.',

            'is_archived.boolean' => 'Поле "Архивировано" должно быть булевым значением.',
        ];
    }

    public function prepareForValidation()
    {
        $this->merge([
            'is_archived' => filter_var($this->is_archived, FILTER_VALIDATE_BOOLEAN),
        ]);
    }
}
