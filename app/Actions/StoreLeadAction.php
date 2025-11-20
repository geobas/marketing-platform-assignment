<?php

namespace App\Actions;

use App\Contracts\LeadRepositoryInterface;
use App\DTOs\LeadData;
use App\Models\Lead;

class StoreLeadAction
{
    /**
     * Constructor
     */
    public function __construct(
        private LeadRepositoryInterface $repository
    ) {}

    /**
     * Execute the action to store a new Lead.
     */
    public function execute(LeadData $data): Lead
    {
        return $this->repository->create([
            'full_name' => $data->fullName,
            'email' => $data->email,
            'consent' => $data->consent,
        ]);
    }
}
