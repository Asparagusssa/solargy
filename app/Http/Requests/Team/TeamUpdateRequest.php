<?php

namespace App\Http\Requests\Team;

use Illuminate\Foundation\Http\FormRequest;

class TeamUpdateRequest extends FormRequest
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
            'name' => ['string', 'max:255', 'sometimes'],
            'description' => ['string', 'max:255', 'sometimes'],
            'image' => ['image', 'nullable', 'mimes:jpeg,png,jpg,gif', 'max:10240', 'sometimes'],
            'phone' => ['string', 'max:255', 'sometimes'],
            'email' => ['email', 'max:255', 'sometimes'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.string' => 'Поле "Название" должно быть строкой.',
            'name.max' => 'Поле "Название" не должно превышать 255 символов.',

            'description.string' => 'Поле "Описание" должно быть строкой.',
            'description.max' => 'Поле "Описание" не должно превышать 255 символов.',

            'image.image' => 'Поле "Изображение" должно быть файлом изображения.',
            'image.nullable' => 'Поле "Изображение" может быть пустым.',
            'image.mimes' => 'Поле "Изображение" должно быть одним из следующих форматов: jpeg, png, jpg, gif.',
            'image.max' => 'Размер изображения не должен превышать 10 МБ.',

            'phone.string' => 'Поле "Телефон" должно быть строкой.',
            'phone.max' => 'Поле "Телефон" не должно превышать 255 символов.',

            'email.email' => 'Поле "Email" должно содержать корректный адрес электронной почты.',
            'email.max' => 'Поле "Email" не должно превышать 255 символов.',
        ];
    }
}
