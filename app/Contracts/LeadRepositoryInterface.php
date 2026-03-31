<?php

declare(strict_types=1);

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
     *
     * @param array<string, mixed> $data
     * @return LengthAwarePaginator<int, Lead>
     */
    public function list(array $data): LengthAwarePaginator;

    /**
     * Update an existing Lead.
     */
    public function update(UpdateLeadData $data): Lead;

    /**
     * Find Lead by ID.
     */
    public function findById(string $id): ?Lead;

    /**
     * Check if a Lead exists by email excluding a specific ID.
     */
    public function existsByEmailExceptId(string $email, string $excludeId): bool;
}
