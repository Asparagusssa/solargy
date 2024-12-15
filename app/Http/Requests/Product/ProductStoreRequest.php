<?php

namespace App\Http\Requests\Product;

class ProductStoreRequest extends BaseFormRequest
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
            'category_id' => ['required', 'exists:categories,id'],
            'name' => ['required', 'max:255'],
            'description' => ['required'],
            'price' => ['required', 'numeric'],
            'is_top' => ['nullable', 'boolean'],
            'photos' => ['array'],
            'photos.*.photo' => ['required_with:photos', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
            'photos.*.order' => ['sometimes', 'integer'],
            'options' => ['array'],
            'options.*.name' => ['required_with:options:', 'string', 'max:255'],
            'options.*.values' => ['required_with:options','array'],
            'options.*.values.*.value' => ['required_with:options.*.values', 'string', 'max:255'],
            'options.*.values.*.price' => ['numeric'],
            'options.*.values.*.image' => ['sometimes', 'image', 'mimes:jpg,png,jpeg,gif', 'max:2048'],
            'properties' => ['array'],
            'properties.*.title' => ['required_with:properties', 'string', 'in:property,recommendation'],
            'properties.*.html' => ['required_with:properties', 'string'],
            'properties.*.file' => ['sometimes', 'file', 'max:10240'],
            'properties.*.image' => ['sometimes','image', 'mimes:jpg,png,jpeg,gif', 'max:2048'],
        ];
    }

    public function prepareForValidation()
    {
        $this->merge([
            'is_top' => filter_var($this->is_top, FILTER_VALIDATE_BOOLEAN),
        ]);
    }
}
