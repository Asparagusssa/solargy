<?php

namespace App\Http\Requests\Company;

use Illuminate\Foundation\Http\FormRequest;

class CompanyUpdateRequest extends FormRequest
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
            'details' => ['sometimes', 'array'],
            'details.*.name' => ['sometimes', 'string'],
            'details.*.office' => ['sometimes', 'string'],
            'details.*.production' => ['sometimes', 'string'],
            'details.*.email' => ['sometimes', 'email'],
            'details.*.phone' => ['sometimes', 'string'],
            'custom-details' => ['array'],
            'custom-details.*.id' => ['sometimes', 'integer'],
            'custom-details.*.title' => ['required_with:custom-details', 'string'],
            'custom-details.*.value' => ['required_with:custom-details', 'string'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.sometimes' => 'Поле "Название" не является обязательным, но должно быть строкой при наличии.',
            'name.string' => 'Поле "Название" должно быть строкой.',
            'name.max' => 'Поле "Название" не должно превышать 255 символов.',

            'details.sometimes' => 'Поле "Параметры" не является обязательным, но должно быть массивом при наличии.',
            'details.array' => 'Поле "Параметры" должно быть массивом.',
            'details.*.name.sometimes' => 'Поле "Название параметра" должно быть строкой при наличии.',
            'details.*.name.string' => 'Поле "Название параметра" должно быть строкой.',
            'details.*.office.sometimes' => 'Поле "Офис параметра" должно быть строкой при наличии.',
            'details.*.office.string' => 'Поле "Офис параметра" должно быть строкой.',
            'details.*.production.sometimes' => 'Поле "Производство параметра" должно быть строкой при наличии.',
            'details.*.production.string' => 'Поле "Производство параметра" должно быть строкой.',
            'details.*.email.sometimes' => 'Поле "Email параметра" должно быть действительным адресом электронной почты при наличии.',
            'details.*.email.email' => 'Поле "Email параметра" должно быть действительным адресом электронной почты.',
            'details.*.phone.sometimes' => 'Поле "Телефон параметра" должно быть строкой при наличии.',
            'details.*.phone.string' => 'Поле "Телефон параметра" должно быть строкой.',

            'custom-details.array' => 'Поле "Пользовательские параметры" должно быть массивом.',
            'custom-details.*.id.sometimes' => 'Поле "ID пользовательского параметра" должно быть числом при наличии.',
            'custom-details.*.id.integer' => 'Поле "ID пользовательского параметра" должно быть целым числом.',
            'custom-details.*.title.required_with' => 'Поле "Заголовок пользовательского параметра" обязательно, если указаны пользовательские параметры.',
            'custom-details.*.title.string' => 'Поле "Заголовок пользовательского параметра" должно быть строкой.',
            'custom-details.*.value.required_with' => 'Поле "Значение пользовательского параметра" обязательно, если указаны пользовательские параметры.',
            'custom-details.*.value.string' => 'Поле "Значение пользовательского параметра" должно быть строкой.',
        ];
    }
}
