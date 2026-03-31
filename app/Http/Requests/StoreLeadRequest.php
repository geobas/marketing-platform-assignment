<?php

declare(strict_types=1);

namespace App\Http\Requests;

use App\DTOs\LeadData;
use App\Rules\NotExampleDomain;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreLeadRequest extends FormRequest
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
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'full_name' => ['required', 'string', 'min:5', 'max:100'],
            'email' => ['required', 'email', 'max:100', 'unique:leads,email', new NotExampleDomain],
            'consent' => ['sometimes', 'boolean'],
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'full_name.required' => 'The full name is required.',
            'full_name.string' => 'The full name must be a string.',
            'full_name.min' => 'The full name must be at least 5 characters.',
            'full_name.max' => 'The full name must not be greater than 100 characters.',
            'email.required' => 'The email is required.',
            'consent.boolean' => 'The consent must be true or false.',
        ];
    }

    /**
     * Convert the validated request data to a LeadData DTO.
     */
    public function toDto(): LeadData
    {
        return new LeadData(
            fullName: $this->validated('full_name'),
            email: $this->validated('email'),
            consent: $this->validated('consent') ?? false,
        );
    }
}
