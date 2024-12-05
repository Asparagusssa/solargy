<?php

namespace App\Http\Requests\Category;

use App\Http\Requests\Product\BaseFormRequest;

class CategoryStoreRequest extends BaseFormRequest
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
            'name' => ['required', 'max:255'],
            'photo' => ['nullable', 'string'],
        ];
    }

    public function messages(): array
    {
        return [
            'parent_id.exists' => 'Поле "parent_id" должно быть существующим.',
            'name.required' => 'Поле "name" является обязательным.',
            'name.max' => 'Поле "name" не должно превышать 255 символов.',
            'photo.string' => 'Поле "photo" должно быть строкой.',
            'level.numeric' => 'Поле "price" должно быть числом.',
        ];
    }
}
