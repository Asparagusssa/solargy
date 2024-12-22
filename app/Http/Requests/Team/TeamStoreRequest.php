<?php

namespace App\Http\Requests\Team;

use Illuminate\Foundation\Http\FormRequest;

class TeamStoreRequest extends FormRequest
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
            'name' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string', 'max:255'],
            'image' => ['required', 'nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:10240'],
            'phone' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Поле "Название" обязательно для заполнения.',
            'name.string' => 'Поле "Название" должно быть строкой.',
            'name.max' => 'Поле "Название" не должно превышать 255 символов.',

            'description.required' => 'Поле "Описание" обязательно для заполнения.',
            'description.string' => 'Поле "Описание" должно быть строкой.',
            'description.max' => 'Поле "Описание" не должно превышать 255 символов.',

            'image.required' => 'Поле "Изображение" обязательно для заполнения.',
            'image.nullable' => 'Поле "Изображение" может быть пустым.',
            'image.image' => 'Поле "Изображение" должно быть файлом изображения.',
            'image.mimes' => 'Поле "Изображение" должно быть одним из следующих форматов: jpeg, png, jpg, gif.',
            'image.max' => 'Размер изображения не должен превышать 10 МБ.',

            'phone.required' => 'Поле "Телефон" обязательно для заполнения.',
            'phone.string' => 'Поле "Телефон" должно быть строкой.',
            'phone.max' => 'Поле "Телефон" не должно превышать 255 символов.',

            'email.required' => 'Поле "Email" обязательно для заполнения.',
            'email.email' => 'Поле "Email" должно содержать корректный адрес электронной почты.',
            'email.max' => 'Поле "Email" не должно превышать 255 символов.',
        ];
    }
}
