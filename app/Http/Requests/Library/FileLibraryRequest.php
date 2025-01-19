<?php

namespace App\Http\Requests\Library;

use Illuminate\Foundation\Http\FormRequest;

class FileLibraryRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'file' => ['required', 'file'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
