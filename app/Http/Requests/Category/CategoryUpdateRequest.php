<?php

namespace App\Http\Requests\Category;

use App\Http\Requests\Product\BaseFormRequest;

class CategoryUpdateRequest extends BaseFormRequest
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
            'parent_id' => ['nullable', 'exists:categories,id'],
            'name' => ['string', 'max:255'],
            'photo' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:10240'],
        ];
    }

    public function messages(): array
    {
        return [
            'parent_id.exists' => 'Выбранная категория-родитель недействительна.',
            'name.string' => 'Поле "Название" должно быть строкой.',
            'name.max' => 'Поле "Название" не должно превышать 255 символов.',
            'photo.image' => 'Поле "Фото" должно быть изображением.',
            'photo.mimes' => 'Фото должно быть в одном из следующих форматов: jpeg, png, jpg, gif.',
            'photo.max' => 'Размер файла фото не должен превышать 10 МБ.',
        ];
    }

}
