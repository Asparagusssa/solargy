<?php

namespace App\Http\Requests\ContactSocial;

use Illuminate\Foundation\Http\FormRequest;

class ContactSocialStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'platform' => ['required', 'string', 'max:255'],
            'url' => ['required', 'string', 'url'],
            'image' => ['required', 'image', 'mimes:jpeg,png,jpg,gif,svg', 'max:10240'],
            'image_footer' => ['required', 'image', 'mimes:jpeg,png,jpg,gif,svg', 'max:10240'],
        ];
    }

    public function messages(): array
    {
        return [
            'platform.required' => 'Поле "Платформа" обязательно для заполнения.',
            'platform.string' => 'Поле "Платформа" должно быть строкой.',
            'platform.max' => 'Поле "Платформа" не должно превышать 255 символов.',

            'url.required' => 'Поле "URL" обязательно для заполнения.',
            'url.string' => 'Поле "URL" должно быть строкой.',
            'url.url' => 'Поле "URL" должно быть действительным адресом.',

            'image.required' => 'Поле "Изображение" обязательно для заполнения.',
            'image.image' => 'Поле "Изображение" должно быть изображением.',
            'image.mimes' => 'Поле "Изображение" должно быть в формате: jpeg, png, jpg, gif, svg.',
            'image.max' => 'Размер файла изображения не должен превышать 10 МБ.',

            'image_footer.required' => 'Поле "Изображение в футере" обязательно для заполнения.',
            'image_footer.image' => 'Поле "Изображение в футере" должно быть изображением.',
            'image_footer.mimes' => 'Поле "Изображение в футере" должно быть в формате: jpeg, png, jpg, gif, svg.',
            'image_footer.max' => 'Размер файла изображения в футере не должен превышать 10 МБ.',
        ];
    }
}
