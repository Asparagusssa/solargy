<?php

namespace App\Http\Requests\News;

use Illuminate\Foundation\Http\FormRequest;

class NewsStoreRequest extends FormRequest
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
            'image' => ['required_without:video', 'image', 'mimes:jpeg,png,jpg,gif,svg', 'max:10240'],
            'video' => ['nullable', 'url', 'starts_with:https://vkvideo.ru/'],
            'date' => ['nullable', 'date'],
            'pinned_until' => ['nullable', 'date'],
            'type' => ['required', 'in:Новости,Блог,Акция'],
            'html' => ['nullable'],
            'promo_id' => ['nullable', 'required_if:type,Акция', 'exists:promos,id'],
        ];
    }

    public function messages()
    {
        return [
            'title.string' => 'Поле "Заголовок" должно быть строкой.',
            'title.max' => 'Поле "Заголовок" не должно превышать 255 символов.',

            'image.image' => 'Поле "Изображение" должно быть файлом изображения.',
            'image.mimes' => 'Поле "Изображение" должно быть одним из следующих типов: jpeg, png, jpg, gif, svg.',
            'image.max' => 'Поле "Изображение" не должно превышать 10 МБ.',
            'image.required_without' => 'Должно быть хотя бы одно из полей "Изображение" или "Видео".',

            'video.url' => 'Поле "Видео" должно быть ссылкой.',
            'video.starts_with' => 'Поле "Видео" должно начинаться с https://vkvideo.ru/',

            'date.date' => 'Поле "Дата" должно быть датой (ГГГГ-MM-ДД).',

            'type.in' => 'Поле "Тип" должно быть одним из следующих значений: Новости, Блог, Акция.',
            'type.required' => 'Поле "Тип" должно быть указано.',
        ];
    }
}
