<?php

namespace App\Http\Requests;

use App\Contracts\LeadRepositoryInterface;
use App\DTOs\UpdateLeadData;
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
            'full_name' => ['required', 'string', 'max:100'],
            'email' => ['required', 'email', 'max:100'],
            'consent' => ['sometimes', 'boolean'],
        ];
    }

    /**
     * Configure the validator instance.
     */
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            /** @phpstan-ignore-next-line */
            $leadId = $this->is('api/*') ? $this->route('lead') : $this->route('lead')->id;

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
        /** @phpstan-ignore-next-line */
        $leadId = $this->is('api/*') ? $this->route('lead') : $this->route('lead')->id;

        return new UpdateLeadData(
            _id: $leadId,
            fullName: $this->validated('full_name'),
            email: $this->validated('email'),
            consent: $this->validated('consent') ?? false,
        );
    }
}
