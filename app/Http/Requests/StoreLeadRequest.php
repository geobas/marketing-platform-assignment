<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\DTOs\LeadData;

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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'full_name' => ['required', 'string', 'max:100'],
            'email'   => ['required', 'email', 'max:100', 'unique:leads,email'],
            'consent'   => ['sometimes', 'boolean'],
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
