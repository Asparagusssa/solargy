<?php

namespace App\Http\Requests\Promo;

use Illuminate\Foundation\Http\FormRequest;

class PromoStoreRequest extends FormRequest
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
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'image' => ['required', 'nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:10240'],
            'start' => ['required', 'date', 'before:end'],
            'end' => ['required', 'date', 'after:start'],
            'is_archived' => ['nullable', 'boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'title.required' => 'Поле "Заголовок" обязательно для заполнения.',
            'title.string' => 'Поле "Заголовок" должно быть строкой.',
            'title.max' => 'Поле "Заголовок" не должно превышать 255 символов.',

            'description.required' => 'Поле "Описание" обязательно для заполнения.',
            'description.string' => 'Поле "Описание" должно быть строкой.',

            'image.required' => 'Поле "Изображение" обязательно для заполнения.',
            'image.nullable' => 'Поле "Изображение" может быть пустым.',
            'image.image' => 'Поле "Изображение" должно быть изображением.',
            'image.mimes' => 'Поле "Изображение" должно быть в формате: jpeg, png, jpg, gif.',
            'image.max' => 'Размер файла изображения не должен превышать 10 МБ.',

            'start.required' => 'Поле "Дата начала" обязательно для заполнения.',
            'start.date' => 'Поле "Дата начала" должно быть датой.',
            'start.before' => 'Дата начала должна быть до даты окончания.',

            'end.required' => 'Поле "Дата окончания" обязательно для заполнения.',
            'end.date' => 'Поле "Дата окончания" должно быть датой.',
            'end.after' => 'Дата окончания должна быть после даты начала.',

            'is_archived.nullable' => 'Поле "Архивировано" может быть пустым.',
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
