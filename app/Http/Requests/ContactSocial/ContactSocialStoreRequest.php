<?php

namespace App\Http\Requests\ContactSocial;

use Illuminate\Foundation\Http\FormRequest;

class ContactSocialStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'platform' => ['required', 'string', 'max:255'],
            'url' => ['required', 'string', 'url'],
            'image' => ['required', 'image', 'mimes:jpeg,png,jpg,gif,svg', 'max:2048'],
        ];
    }

}
