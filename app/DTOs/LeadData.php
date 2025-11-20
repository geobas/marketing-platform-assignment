<?php

namespace App\DTOs;

/**
 * Data Transfer Object for Lead.
 */
final readonly class LeadData
{
    public function __construct(
        public string $fullName,
        public string $email,
        public bool $consent,
    ) {}
}
