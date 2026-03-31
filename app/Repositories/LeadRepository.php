<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Contracts\LeadRepositoryInterface;
use App\DTOs\LeadData;
use App\DTOs\UpdateLeadData;
use App\Exceptions\LeadRepositoryException;
use App\Models\Lead;
use Illuminate\Pagination\LengthAwarePaginator;
use MongoDB\BSON\ObjectId;
use Throwable;

class LeadRepository implements LeadRepositoryInterface
{
    /**
     * Leads per page for pagination.
     */
    const PER_PAGE = 5;

    /**
     * Initialize repository.
     */
    public function __construct(
        private readonly Lead $lead,
    ) {}

    /**
     * Create a new lead.
     *
     * @throws LeadRepositoryException
     */
    public function create(LeadData $data): Lead
    {
        try {
            /** @var Lead $lead */
            $lead = $this->lead->create($data->toArray());

            return $lead;
        } catch (Throwable $e) {
            throw new LeadRepositoryException(message: 'Failed to create lead.', status: $e->getCode());
        }
    }

    /**
     * Paginate leads.
     *
     * @throws LeadRepositoryException
     */
    public function list(array $data): LengthAwarePaginator
    {
        try {
            return $this->lead
                ->paginate($data['per_page'] ?? self::PER_PAGE)
                ->withQueryString()
                ->appends([
                    'per_page' => $data['per_page'] ?? self::PER_PAGE,
                ]);
        } catch (Throwable $e) {
            throw new LeadRepositoryException(message: 'Failed to list leads.', status: $e->getCode());
        }
    }

    /**
     * Update a lead.
     *
     * @throws LeadRepositoryException
     */
    public function update(UpdateLeadData $data): Lead
    {
        try {
            /** @var Lead|null $lead */
            $lead = $this->lead->find(new ObjectId($data->_id));

            if (! $lead) {
                throw new LeadRepositoryException("Lead not found: {$data->_id}");
            }

            return tap($lead, function (Lead $lead) use ($data): void {
                $lead->update([
                    'full_name' => $data->fullName,
                    'email' => $data->email,
                    'consent' => $data->consent ?? false,
                ]);
            });
        } catch (Throwable $e) {
            throw new LeadRepositoryException(message: 'Failed to update lead.', status: $e->getCode());
        }
    }

    /**
     * Find a lead by ID.
     *
     * @throws LeadRepositoryException
     */
    public function findById(string $id): ?Lead
    {
        try {
            /** @var Lead|null $lead */
            $lead = $this->lead->find($id);

            return $lead;
        } catch (Throwable $e) {
            throw new LeadRepositoryException(message: "Failed to fetch lead with ID: {$id}", status: $e->getCode());
        }
    }

    /**
     * Check if email exists excluding a specific ID.
     *
     * @throws LeadRepositoryException
     */
    public function existsByEmailExceptId(string $email, string $excludeId): bool
    {
        try {
            return $this->lead
                ->where('email', $email)
                ->where('_id', '!=', $excludeId)
                ->exists();
        } catch (Throwable $e) {
            throw new LeadRepositoryException(message: 'Failed to check email uniqueness.', status: $e->getCode());
        }
    }
}
