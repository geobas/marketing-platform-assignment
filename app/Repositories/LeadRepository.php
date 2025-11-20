<?php

namespace App\Repositories;

use App\Contracts\LeadRepositoryInterface;
use App\Models\Lead;

class LeadRepository implements LeadRepositoryInterface
{
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
}
