<?php

namespace App\Http\Requests\PurchasePlace;

use Illuminate\Foundation\Http\FormRequest;

class PurchasePlaceUpdateRequest extends FormRequest
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
            'name' => ['sometimes', 'string', 'max:255'],
            'type' => ['sometimes', 'string', 'in:marketplace,partner,store,retailer'],
            'url' => ['sometimes', 'nullable', 'string', 'url'],
            'image' => ['sometimes', 'nullable', 'image', 'mimes:jpeg,png,jpg,gif,svg', 'max:10240'],
        ];
    }
}
