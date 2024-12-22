<?php

namespace App\Http\Requests\Contact;

use Illuminate\Foundation\Http\FormRequest;

class ContactStoreRequest extends FormRequest
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
            'address' => ['required', 'string'],
            'phone' => ['required', 'string'],
            'email' => ['required', 'email'],
            'map' => ['required', 'string'],
        ];
    }

    public function messages(): array
    {
        return [
            'address.required' => 'Поле "Адрес" обязательно для заполнения.',
            'address.string' => 'Поле "Адрес" должно быть строкой.',

            'phone.required' => 'Поле "Телефон" обязательно для заполнения.',
            'phone.string' => 'Поле "Телефон" должно быть строкой.',

            'email.required' => 'Поле "Email" обязательно для заполнения.',
            'email.email' => 'Поле "Email" должно быть действительным адресом электронной почты.',

            'map.required' => 'Поле "Карта" обязательно для заполнения.',
            'map.string' => 'Поле "Карта" должно быть строкой.',
        ];
    }

}
