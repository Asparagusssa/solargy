<?php

namespace App\Http\Requests\Team;

use Illuminate\Foundation\Http\FormRequest;

class TeamUpdateRequest extends FormRequest
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
            'name' => ['string', 'max:255', 'sometimes'],
            'description' => ['string', 'max:255', 'sometimes'],
            'image' => ['image', 'mimes:jpeg,png,jpg,gif', 'max:5120', 'sometimes'],
            'phone' => ['string', 'max:255', 'sometimes'],
            'email' => ['email', 'max:255', 'sometimes'],
        ];
    }
}
