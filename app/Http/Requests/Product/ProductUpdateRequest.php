<?php

namespace App\Http\Requests\Product;

class ProductUpdateRequest extends BaseFormRequest
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
            'category_id' => ['exists:categories,id'],
            'name' => ['string', 'max:255'],
            'description' => ['string'],
            'price' => ['numeric'],
            'is_top' => ['boolean'],
            'photos' => ['array'],
            'photos.*.id' => ['integer', 'exists:product_photos,id'],
            'photos.*.photo' => ['sometimes','image', 'mimes:jpg,png,jpeg,gif', 'max:2048'],
            'photos.*.order' => ['integer'],
            'options' => ['array'],
            'options.*.id' => ['integer', 'exists:options,id'],
            'options.*.name' => ['string'],
            'options.*.values' => ['array'],
            'options.*.values.*.id' => ['integer', 'exists:values,id'],
            'options.*.values.*.value' => ['string'],
            'options.*.values.*.image' => ['sometimes', 'image', 'mimes:jpg,png,jpeg,gif', 'max:2048'],
            'properties' => ['array'],
            'properties.*.id' => ['integer', 'exists:product_properties,id'],
            'properties.*.title' => ['string', 'in:property,recommendation'],
            'properties.*.html' => ['string'],
            'properties.*.file' => ['sometimes', 'file', 'max:10240'],
            'properties.*.image' => ['sometimes','image', 'mimes:jpg,png,jpeg,gif', 'max:2048'],
        ];
    }

//    public function messages(): array
//    {
//        return [
//            'category_id.exists' => 'Поле "category_id" должно быть существующим.',
//            'name.string' => 'Поле "name" должно быть строкой.',
//            'name.max' => 'Поле "name" не должно превышать 255 символов.',
//            'description.string' => 'Поле "description" должно быть строкой.',
//            'price.numeric' => 'Поле "price" должно быть числом.',
//            'is_top.boolean' => 'Поле "is_top" должно быть логическим типом.',
//            'photos.array' => 'Поле "photos" должно быть массивом.',
//            'photos.*.photo.string' => 'Поле "photo" должно быть строкой.',
//            'photos.*.is_main.boolean' => 'Поле "is_main" должно быть логическим типом.',
//            'options.*.values.*.value.required_without' => 'Поле "value" обязательно для заполнения, если не указан "options[id]".',
//        ];
//    }
}
