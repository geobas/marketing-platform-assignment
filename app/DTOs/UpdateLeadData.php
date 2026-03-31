<?php

declare(strict_types=1);

namespace App\DTOs;

/**
 * Data Transfer Object for Lead data.
 */
final readonly class UpdateLeadData
{
    /**
     * Constructor
     */
    public function __construct(
        public string $_id,
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
            '_id' => $this->_id,
            'full_name' => $this->fullName,
            'email' => $this->email,
            'consent' => $this->consent,
        ];
    }
}
