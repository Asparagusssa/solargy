<?php

namespace App\Http\Requests\PurchasePlace;

use Illuminate\Foundation\Http\FormRequest;

class PurchasePlaceStoreRequest extends FormRequest
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
            'name' => ['required', 'string', 'max:255'],
            'type' => ['required', 'string', 'in:marketplace,partner,store,retailer'],
            'url' => ['sometimes','nullable', 'url'],
            'image' => ['sometimes', 'nullable', 'image', 'mimes:jpeg,png,jpg,gif,svg', 'max:10240'],
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'url' => $this->url === 'null' ? '' : $this->url,
        ]);
    }
}
