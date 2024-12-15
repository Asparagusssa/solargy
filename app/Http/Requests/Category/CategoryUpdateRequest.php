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
            'parent_id.exists' => 'Поле "parent_id" должно быть существующим.',
            'name.string' => 'Поле "name" должно быть строкой.',
            'name.max' => 'Поле "name" не должно превышать 255 символов.',
        ];
    }
}
