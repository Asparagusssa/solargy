<?php

namespace App\Http\Requests\ContactSocial;

use Illuminate\Foundation\Http\FormRequest;

class ContactSocialUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'platform' => ['sometimes', 'string', 'max:255'],
            'url' => ['sometimes', 'string', 'url'],
            'image' => ['sometimes', 'nullable', 'image', 'mimes:jpg,png,jpeg,gif,svg', 'max:10240'],
            'image_footer' => ['sometimes', 'nullable', 'image', 'mimes:jpg,png,jpeg,gif,svg', 'max:10240'],
        ];
    }

    public function messages(): array
    {
        return [
            'platform.sometimes' => 'Поле "Платформа" не обязательно, но должно быть строкой при наличии.',
            'platform.string' => 'Поле "Платформа" должно быть строкой.',
            'platform.max' => 'Поле "Платформа" не должно превышать 255 символов.',

            'url.sometimes' => 'Поле "URL" не обязательно, но должно быть строкой при наличии.',
            'url.string' => 'Поле "URL" должно быть строкой.',
            'url.url' => 'Поле "URL" должно быть действительным адресом.',

            'image.sometimes' => 'Поле "Изображение" не обязательно, но должно быть изображением при наличии.',
            'image.nullable' => 'Поле "Изображение" может быть пустым.',
            'image.image' => 'Поле "Изображение" должно быть изображением.',
            'image.mimes' => 'Поле "Изображение" должно быть в формате: jpg, png, jpeg, gif, svg.',
            'image.max' => 'Размер файла изображения не должен превышать 10 МБ.',

            'image_footer.sometimes' => 'Поле "Изображение в футере" не обязательно, но должно быть изображением при наличии.',
            'image_footer.nullable' => 'Поле "Изображение в футере" может быть пустым.',
            'image_footer.image' => 'Поле "Изображение в футере" должно быть изображением.',
            'image_footer.mimes' => 'Поле "Изображение в футере" должно быть в формате: jpg, png, jpeg, gif, svg.',
            'image_footer.max' => 'Размер файла изображения в футере не должен превышать 10 МБ.',
        ];
    }
}
