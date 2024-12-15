<?php

namespace App\Http\Requests\ContactSocial;

use Illuminate\Foundation\Http\FormRequest;

class ContactSocialUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'platform' => ['sometimes', 'string', 'max:255'],
            'url' => ['sometimes', 'string', 'url'],
            'image' => ['sometimes', 'image', 'mimes:jpg,png,jpeg,gif,svg', 'max:10240'],
        ];
    }

}
