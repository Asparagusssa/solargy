<?php

namespace App\Http\Requests\MainBanner;

use App\Http\Requests\Product\BaseFormRequest;
use Illuminate\Validation\Rule;

class MainBannerUpdateRequest extends BaseFormRequest
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
            'product_id' => ['sometimes', 'exists:products,id'],
            'title' => ['sometimes', 'string', 'max:255'],
            'description' => ['sometimes', 'string'],
            'image' => ['sometimes', 'image', 'max:10240'],
            'order' => ['integer',
                Rule::unique('main_banners')->ignore($this->main_banner->id)
            ]
        ];
    }
}
