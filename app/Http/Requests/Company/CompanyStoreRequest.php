<?php

namespace App\Http\Requests\Company;

use Illuminate\Foundation\Http\FormRequest;

class CompanyStoreRequest extends FormRequest
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
            'details' => ['required', 'array'],
            'details.*.name' => ['required', 'string'],
            'details.*.office' => ['sometimes', 'string'],
            'details.*.production' => ['sometimes', 'string'],
            'details.*.email' => ['sometimes', 'email'],
            'details.*.phone' => ['sometimes', 'string'],
            'custom-details' => ['array'],
            'custom-details.*.title' => ['required_with:custom-details', 'string'],
            'custom-details.*.value' => ['required_with:custom-details', 'string'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Поле "Название" обязательно для заполнения.',
            'name.string' => 'Поле "Название" должно быть строкой.',
            'name.max' => 'Поле "Название" не должно превышать 255 символов.',

            'details.required' => 'Поле "Параметры" обязательно для заполнения.',
            'details.array' => 'Поле "Параметры" должно быть массивом.',
            'details.*.name.required' => 'Поле "Название параметра" обязательно для заполнения.',
            'details.*.name.string' => 'Поле "Название параметра" должно быть строкой.',
            'details.*.office.string' => 'Поле "Офис" должно быть строкой.',
            'details.*.production.string' => 'Поле "Производство" должно быть строкой.',
            'details.*.email.email' => 'Поле "Email" должно быть действительным адресом электронной почты.',
            'details.*.phone.string' => 'Поле "Телефон" должно быть строкой.',

            'custom-details.array' => 'Поле "Пользовательские параметры" должно быть массивом.',
            'custom-details.*.title.required_with' => 'Поле "Заголовок пользовательского параметра" обязательно, если указаны пользовательские детали.',
            'custom-details.*.title.string' => 'Поле "Заголовок пользовательского параметра" должно быть строкой.',
            'custom-details.*.value.required_with' => 'Поле "Значение пользовательского параметра" обязательно, если указаны пользовательские детали.',
            'custom-details.*.value.string' => 'Поле "Значение пользовательского параметра" должно быть строкой.',
        ];
    }

}
