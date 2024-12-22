<?php

namespace App\Http\Requests\Contact;

use Illuminate\Foundation\Http\FormRequest;

class ContactUpdateRequest extends FormRequest
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
            'address' => ['sometimes', 'string'],
            'phone' => ['sometimes', 'string'],
            'email' => ['sometimes', 'email'],
            'map' => ['sometimes', 'string'],
        ];
    }

    public function messages(): array
    {
        return [
            'address.sometimes' => 'Поле "Адрес" не обязательно, но должно быть строкой при наличии.',
            'address.string' => 'Поле "Адрес" должно быть строкой.',

            'phone.sometimes' => 'Поле "Телефон" не обязательно, но должно быть строкой при наличии.',
            'phone.string' => 'Поле "Телефон" должно быть строкой.',

            'email.sometimes' => 'Поле "Email" не обязательно, но должно быть действительным адресом электронной почты при наличии.',
            'email.email' => 'Поле "Email" должно быть действительным адресом электронной почты.',

            'map.sometimes' => 'Поле "Карта" не обязательно, но должно быть строкой при наличии.',
            'map.string' => 'Поле "Карта" должно быть строкой.',
        ];
    }
}
