<?php

namespace App\Http\Requests\SubBanner;

use Illuminate\Foundation\Http\FormRequest;

class SubBannerStoreRequest extends FormRequest
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
            'title' => ['nullable', 'string', 'max:255'],
            'image' => ['required', 'image', 'mimes:jpg,png,jpeg,gif', 'max:5120'],
            'order' => ['numeric', 'unique:sub_banners'],
        ];
    }
}
