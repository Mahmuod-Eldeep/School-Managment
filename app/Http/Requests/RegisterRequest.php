<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
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
            'name' => 'required|max:255',
            'email.required' => 'Email address is required.',
            'email.email' => 'Email address is not valid.',
            'email.unique' => 'Email address has already been taken.',
            'phoneNumber.required' => 'Phone number is required.',
            'phoneNumber.regex' => 'Phone number format is invalid.',
            'phoneNumber.unique' => 'Phone number has already been taken.',
            "classRoom" => 'required',
            'password.required' => 'Password is required.',
            'password.min' => 'Password must be at least 8 characters.',
            'password.confirmed' => 'Password confirmation does not match.',
            'password.regex' => 'Password must contain at least one uppercase letter, one lowercase letter, and one special character.',
            'status' => 'required'
        ];
    }
}
