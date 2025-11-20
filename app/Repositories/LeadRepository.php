<?php

namespace App\Repositories;

use App\Contracts\LeadRepositoryInterface;
use App\DTOs\LeadData;
use App\DTOs\UpdateLeadData;
use App\Models\Lead;
use Illuminate\Pagination\LengthAwarePaginator;
use MongoDB\BSON\ObjectId;

class LeadRepository implements LeadRepositoryInterface
{
    /**
     * Leads per page for pagination.
     */
    const PER_PAGE = 5;

    /**
     * Constructor
     */
    public function __construct(
        private Lead $lead,
    ) {}

    public function create(LeadData $data): Lead
    {
        /** @var Lead $lead */
        $lead = $this->lead->create($data->toArray());

        return $lead;
    }

    public function list(array $data): LengthAwarePaginator
    {
        return $this->lead
            ->paginate($data['per_page'] ?? self::PER_PAGE)
            ->withQueryString()
            ->appends([
                'per_page' => $data['per_page'] ?? self::PER_PAGE,
            ]);
    }

    public function update(UpdateLeadData $data): Lead
    {
        /** @var Lead $lead */
        $lead = $this->lead->find(new ObjectId($data->_id));

        return tap(
            $lead,
            function (Lead $lead) use ($data) {
                $lead->update([
                    'full_name' => $data->fullName,
                    'email' => $data->email,
                    'consent' => $data->consent ?? false,
                ]);
            }
        );
    }

    public function findById(string $id): ?Lead
    {
        /** @var Lead|null $lead */
        $lead = $this->lead->find($id);

        return $lead;
    }

    public function existsByEmailExceptId(string $email, string $excludeId): bool
    {
        return $this->lead->where('email', $email)
            ->where('_id', '!=', $excludeId)
            ->exists();
    }
}
