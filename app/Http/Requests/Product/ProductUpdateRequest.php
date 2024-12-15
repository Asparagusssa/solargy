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
            'options.*.values.*.price' => ['numeric'],
            'options.*.values.*.image' => ['nullable', 'image', 'mimes:jpg,png,jpeg,gif', 'max:2048'],
            'properties' => ['array'],
            'properties.*.id' => ['integer', 'exists:product_properties,id'],
            'properties.*.title' => ['string', 'in:property,recommendation'],
            'properties.*.html' => ['string'],
            'properties.*.file' => ['nullable', 'file', 'max:10240'],
            'properties.*.image' => ['nullable','image', 'mimes:jpg,png,jpeg,gif', 'max:2048'],
        ];
    }

    public function prepareForValidation()
    {
        $this->merge([
            'is_top' => filter_var($this->is_top, FILTER_VALIDATE_BOOLEAN),
        ]);
    }
}
