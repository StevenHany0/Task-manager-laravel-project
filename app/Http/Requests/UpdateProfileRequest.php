<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProfileRequest extends FormRequest
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
            'bio' => 'sometimes|nullable|string|max:1000',
            'phone' => 'sometimes|string|min:6',
            'address' => 'sometimes|nullable|string|max:255',
            'date_of_birth' => 'sometimes|nullable|date|before:today',
            'image' => 'sometimes|image|mimes:png,jpg,jpeg,gif'
        ];
    }

    public function messages(): array
    {
        return [
            'bio.string' => 'The bio must be a string.',
            'bio.max' => 'The bio may not be greater than 1000 characters.',
            'phone.min' => 'The phone number must be at least 6 numbers.',
            'address.string' => 'The address must be a string.',
            'address.max' => 'The address may not be greater than 255 characters.',
            'date_of_birth.date' => 'The date of birth must be a valid date.',
            'date_of_birth.before' => 'The date of birth must be a date before today.',
            'image.image' => 'The image must be there.',
            'image.required' => 'The image field is required.',
            'image.mimes' => 'The image must be a file of type: png, jpg, jpeg, gif.'
        ];
    }
}


