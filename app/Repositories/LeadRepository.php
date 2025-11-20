<?php

namespace App\Repositories;

use App\Contracts\LeadRepositoryInterface;
use App\Models\Lead;
use Illuminate\Pagination\LengthAwarePaginator;

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

    public function create(array $data): Lead
    {
        return $this->lead->create($data);
    }

    public function list(array $data): LengthAwarePaginator
    {
        return $this->lead->paginate($data['per_page'] ?? self::PER_PAGE)->appends([
            'per_page' => $data['per_page'] ?? self::PER_PAGE,
        ]);
    }
}
