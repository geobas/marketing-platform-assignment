<?php

namespace App\Contracts;

use App\Models\Lead;
use Illuminate\Pagination\LengthAwarePaginator;

interface LeadRepositoryInterface
{
    /**
     * Create a new Lead.
     */
    public function create(array $data): Lead;

    /**
     * List all Leads.
     */
    public function list(array $data): LengthAwarePaginator;
}
