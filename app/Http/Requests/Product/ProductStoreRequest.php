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
            'keywords' => ['nullable', 'string'],
            'photos' => ['array'],
            'photos.*.photo' => ['nullable', 'file', 'mimes:jpeg,png,jpg,gif', 'max:51200',],
            'photos.*.video' => ['nullable', 'url', 'starts_with:https://vkvideo.ru/'],
            'photos.*.order' => ['sometimes', 'integer'],
            'options' => ['array'],
            'options.*.name' => ['required_with:options:', 'string', 'max:255'],
            'options.*.values' => ['required_with:options','array'],
            'options.*.values.*.value' => ['required_with:options.*.values', 'string', 'max:255'],
            'options.*.values.*.price' => ['numeric'],
            'options.*.values.*.image' => ['nullable', 'image', 'mimes:jpg,png,jpeg,gif', 'max:10240'],
            'properties' => ['array'],
            'properties.*.title' => ['required_with:properties', 'string', 'in:property,description,photo,instruction,recommendation,guaranty'],
            'properties.*.html' => ['required_with:properties', 'string'],
            'properties.*.file' => ['nullable', 'file', 'max:10240'],
            'properties.*.filename' => ['nullable', 'string', 'max:255'],
            'properties.*.image' => ['nullable','image', 'mimes:jpg,png,jpeg,gif', 'max:10240'],
        ];
    }

    public function messages(): array
    {
        return [
            'category_id.required' => 'Поле "Категория" обязательно для заполнения.',
            'category_id.exists' => 'Категория с указанным "ID" не существует.',

            'name.required' => 'Поле "Название" обязательно для заполнения.',
            'name.max' => 'Поле "Название" не должно превышать 255 символов.',

            'description.required' => 'Поле "Описание" обязательно для заполнения.',

            'price.required' => 'Поле "Цена" обязательно для заполнения.',
            'price.numeric' => 'Поле "Цена" должно быть числовым значением.',

            'is_top.nullable' => 'Поле "Является ли топом" может быть пустым.',
            'is_top.boolean' => 'Поле "Является ли топом" должно быть булевым значением.',

            'photos.array' => 'Поле "Фотографии" должно быть массивом.',
            'photos.*.photo.required_with' => 'Поле "Фото" обязательно, если указаны другие фотографии.',
            'photos.*.photo.image' => 'Поле "Фото" должно быть изображением.',
            'photos.*.photo.mimes' => 'Поле "Фото" должно быть в формате: jpeg, png, jpg, gif.',
            'photos.*.photo.max' => 'Размер файла изображения не должен превышать 10 МБ.',
            'photos.*.order.sometimes' => 'Поле "Порядок" не обязательно, но если указано, оно должно быть числовым значением.',

            'options.array' => 'Поле "Опции" должно быть массивом.',
            'options.*.name.required_with' => 'Поле "Название опции" обязательно, если указаны другие опции.',
            'options.*.name.string' => 'Поле "Название опции" должно быть строкой.',
            'options.*.name.max' => 'Поле "Название опции" не должно превышать 255 символов.',
            'options.*.values.required_with' => 'Поле "Значения опции" обязательно, если указаны другие значения.',
            'options.*.values.array' => 'Поле "Значения опции" должно быть массивом.',
            'options.*.values.*.value.required_with' => 'Поле "Значение" обязательно, если указаны другие значения.',
            'options.*.values.*.value.string' => 'Поле "Значение" должно быть строкой.',
            'options.*.values.*.value.max' => 'Поле "Значение" не должно превышать 255 символов.',
            'options.*.values.*.price.numeric' => 'Поле "Цена" должно быть числовым значением.',
            'options.*.values.*.image.nullable' => 'Поле "Изображение" может быть пустым.',
            'options.*.values.*.image.image' => 'Поле "Изображение" должно быть изображением.',
            'options.*.values.*.image.mimes' => 'Поле "Изображение" должно быть в формате: jpeg, png, jpg, gif.',
            'options.*.values.*.image.max' => 'Размер файла изображения не должен превышать 10 МБ.',

            'properties.array' => 'Поле "Свойства" должно быть массивом.',
            'properties.*.title.required_with' => 'Поле "Название свойства" обязательно, если указаны другие свойства.',
            'properties.*.title.string' => 'Поле "Название свойства" должно быть строкой.',
            'properties.*.title.in' => 'Поле "Название свойства" должно быть одним из значений: property, description, photo, instruction, recommendation, guaranty.',
            'properties.*.html.required_with' => 'Поле "HTML" обязательно, если указаны другие свойства.',
            'properties.*.html.string' => 'Поле "HTML" должно быть строкой.',
            'properties.*.file.nullable' => 'Поле "Файл" может быть пустым.',
            'properties.*.file.file' => 'Поле "Файл" должно быть файлом.',
            'properties.*.file.max' => 'Размер файла не должен превышать 10 МБ.',
            'properties.*.image.nullable' => 'Поле "Изображение" может быть пустым.',
            'properties.*.image.image' => 'Поле "Изображение" должно быть изображением.',
            'properties.*.image.mimes' => 'Поле "Изображение" должно быть в формате: jpeg, png, jpg, gif.',
            'properties.*.image.max' => 'Размер файла изображения не должен превышать 10 МБ.',
        ];
    }


    public function prepareForValidation()
    {
        $this->merge([
            'is_top' => filter_var($this->is_top, FILTER_VALIDATE_BOOLEAN),
        ]);
    }
}
