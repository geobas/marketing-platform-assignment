<?php

namespace App\Contracts;

use App\DTOs\LeadData;
use App\DTOs\UpdateLeadData;
use App\Models\Lead;
use Illuminate\Pagination\LengthAwarePaginator;

interface LeadRepositoryInterface
{
    /**
     * Create a new Lead.
     */
    public function create(LeadData $data): Lead;

    /**
     * List all Leads.
     */
    public function list(array $data): LengthAwarePaginator;

    /**
     * Update an existing Lead.
     */
    public function update(UpdateLeadData $data): bool;

    /**
     * Find Lead by ID.
     */
    public function findById(string $id): ?Lead;

    /**
     * Check if a Lead exists by email excluding a specific ID.
     */
    public function existsByEmailExceptId(string $email, string $excludeId): bool;
}
