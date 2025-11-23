<?php

namespace App\Http\Requests;

use App\Contracts\LeadRepositoryInterface;
use App\DTOs\UpdateLeadData;
use App\Rules\NotExampleDomain;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

class UpdateLeadRequest extends FormRequest
{
    /**
     * Override the constructor.
     */
    public function __construct(
        private LeadRepositoryInterface $repository
    ) {
        parent::__construct();
    }

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
            'full_name' => ['required', 'string', 'min:5', 'max:100'],
            'email' => ['required', 'email', 'max:100', new NotExampleDomain],
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
     * Configure the validator instance.
     */
    public function withValidator(Validator $validator): void
    {
        $validator->after(function ($validator) {
            $leadId = $this->getLeadId();

            if (empty($this->repository->findById($leadId))) {
                $validator->errors()->add('lead', 'Lead not found.');
            }

            $email = $this->validated('email');

            if (! empty($email) && $this->repository->existsByEmailExceptId($email, $leadId)) {
                $validator->errors()->add('email', 'The email has already been taken.');
            }
        });
    }

    /**
     * Convert the validated request data to a LeadData DTO.
     */
    public function toDto(): UpdateLeadData
    {
        $leadId = $this->getLeadId();

        return new UpdateLeadData(
            _id: $leadId,
            fullName: $this->validated('full_name'),
            email: $this->validated('email'),
            consent: $this->validated('consent') ?? false,
        );
    }

    /**
     * Get the lead ID from the route, regardless of API or Web.
     */
    private function getLeadId(): mixed
    {
        /** @phpstan-ignore-next-line */
        return $this->is('api/*') ? $this->route('lead') : $this->route('lead')->id;
    }
}
