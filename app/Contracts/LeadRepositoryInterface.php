<?php

namespace App\Contracts;

use App\Models\Lead;

interface LeadRepositoryInterface
{
    /**
     * Create a new Lead.
     */
    public function create(array $data): Lead;
}
