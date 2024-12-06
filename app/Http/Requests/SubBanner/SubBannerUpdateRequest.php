<?php

namespace App\Http\Requests\SubBanner;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SubBannerUpdateRequest extends FormRequest
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
            'title' => ['string', 'max:255', 'sometimes'],
            'image' => ['image', 'mimes:jpg,png,jpeg,gif', 'max:2048'],
            'order' => ['integer',
                Rule::unique('sub_banners')->ignore($this->sub_banner->id)
            ]
        ];
    }
}
