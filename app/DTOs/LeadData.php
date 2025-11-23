<?php

namespace App\DTOs;

/**
 * Data Transfer Object for Lead.
 */
final readonly class LeadData
{
    /**
     * Constructor
     */
    public function __construct(
        public string $fullName,
        public string $email,
        public bool $consent,
    ) {}

    /**
     * Convert DTO to array.
     *
     * @return array<string, string|bool>
     */
    public function toArray(): array
    {
        return [
            'full_name' => $this->fullName,
            'email' => $this->email,
            'consent' => $this->consent,
        ];
    }
}
