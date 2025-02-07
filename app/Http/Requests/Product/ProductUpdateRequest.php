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
            'photos.*.photo' => ['required_with:photos', 'file', 'mimes:jpeg,png,jpg,gif,mp4,avi,mov', 'max:51200',],
            'photos.*.order' => ['sometimes', 'integer'],
            'options' => ['array'],
            'options.*.id' => ['integer', 'exists:options,id'],
            'options.*.name' => ['string'],
            'options.*.values' => ['array'],
            'options.*.values.*.id' => ['integer', 'exists:values,id'],
            'options.*.values.*.value' => ['string'],
            'options.*.values.*.price' => ['numeric'],
            'options.*.values.*.image' => ['nullable', 'image', 'mimes:jpg,png,jpeg,gif', 'max:10240'],
            'options.*.values.*.order' => ['nullable', 'integer', 'min:0', 'max:155'],
            'properties' => ['array'],
            'properties.*.id' => ['integer', 'exists:product_properties,id'],
            'properties.*.title' => ['string', 'in:property,description,photo,instruction,recommendation,guaranty'],
            'properties.*.html' => ['string'],
            'properties.*.file' => ['nullable', 'file', 'max:10240'],
            'properties.*.filename' => ['required_with:properties.*.file-library', 'string', 'max:255'],
            'properties.*.image' => ['nullable','image', 'mimes:jpg,png,jpeg,gif', 'max:10240'],
            'properties.*.from-library' => ['boolean'],
            'properties.*.files' => ['array'],
            'properties.*.files.*.file_path' => ['string', 'max:255'],
            'properties.*.files.*.file_name' => ['string', 'max:255'],
        ];
    }

    public function messages(): array
    {
        return [
            'category_id.exists' => 'Категория с указанным "ID" не существует.',

            'name.string' => 'Поле "Название" должно быть строкой.',
            'name.max' => 'Поле "Название" не должно превышать 255 символов.',

            'description.string' => 'Поле "Описание" должно быть строкой.',

            'price.numeric' => 'Поле "Цена" должно быть числовым значением.',

            'is_top.boolean' => 'Поле "Является ли топом" должно быть булевым значением.',

            'photos.array' => 'Поле "Фотографии" должно быть массивом.',
            'photos.*.id.integer' => 'Поле "ID фотографии" должно быть целым числом.',
            'photos.*.id.exists' => 'Фотография с указанным "ID" не существует.',
            'photos.*.photo.sometimes' => 'Поле "Фото" может быть пустым, но если оно указано, должно быть изображением.',
            'photos.*.photo.max' => 'Размер файла фотографии не должен превышать 10 МБ.',
            'photos.*.order.sometimes' => 'Поле "Порядок" не обязательно, но если указано, оно должно быть целым числом.',

            'options.array' => 'Поле "Опции" должно быть массивом.',
            'options.*.id.integer' => 'Поле "ID опции" должно быть целым числом.',
            'options.*.id.exists' => 'Опция с указанным "ID" не существует.',
            'options.*.name.string' => 'Поле "Название опции" должно быть строкой.',
            'options.*.values.array' => 'Поле "Значения опции" должно быть массивом.',
            'options.*.values.*.id.integer' => 'Поле "ID значения" должно быть целым числом.',
            'options.*.values.*.id.exists' => 'Значение с указанным "ID" не существует.',
            'options.*.values.*.value.string' => 'Поле "Значение" должно быть строкой.',
            'options.*.values.*.price.numeric' => 'Поле "Цена" должно быть числовым значением.',
            'options.*.values.*.image.nullable' => 'Поле "Изображение" может быть пустым.',
            'options.*.values.*.image.image' => 'Поле "Изображение" должно быть изображением.',
            'options.*.values.*.image.mimes' => 'Поле "Изображение" должно быть в формате: jpg, png, jpeg, gif.',
            'options.*.values.*.image.max' => 'Размер файла изображения не должен превышать 10 МБ.',

            'properties.array' => 'Поле "Свойства" должно быть массивом.',
            'properties.*.id.integer' => 'Поле "ID свойства" должно быть целым числом.',
            'properties.*.id.exists' => 'Свойство с указанным "ID" не существует.',
            'properties.*.title.string' => 'Поле "Название свойства" должно быть строкой.',
            'properties.*.title.in' => 'Поле "Название свойства" должно быть одним из значений: property, description, photo, instruction, recommendation, guaranty.',
            'properties.*.html.string' => 'Поле "HTML" должно быть строкой.',
            'properties.*.file.nullable' => 'Поле "Файл" может быть пустым.',
            'properties.*.file.file' => 'Поле "Файл" должно быть файлом.',
            'properties.*.file.max' => 'Размер файла не должен превышать 10 МБ.',
            'properties.*.image.nullable' => 'Поле "Изображение" может быть пустым.',
            'properties.*.image.image' => 'Поле "Изображение" должно быть изображением.',
            'properties.*.image.mimes' => 'Поле "Изображение" должно быть в формате: jpg, png, jpeg, gif.',
            'properties.*.image.max' => 'Размер файла изображения не должен превышать 10 МБ.',
        ];
    }

    public function prepareForValidation()
    {
        if (isset($this->properties) && is_array($this->properties)) {
            $properties = array_map(function ($property) {
                if (isset($property['from-library'])) {
                    $property['from-library'] = filter_var($property['from-library'], FILTER_VALIDATE_BOOLEAN);
                }
                return $property;
            }, $this->properties);

            $this->merge([
                'properties' => $properties,
                'is_top' => filter_var($this->is_top, FILTER_VALIDATE_BOOLEAN),
            ]);
        }
    }
}
