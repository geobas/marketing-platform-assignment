<?php

namespace App\Actions;

use App\Contracts\LeadRepositoryInterface;
use App\DTOs\UpdateLeadData;
use App\Models\Lead;

class UpdateLeadAction
{
    /**
     * Constructor
     */
    public function __construct(
        private LeadRepositoryInterface $repository
    ) {}

    /**
     * Execute the action to update an existing Lead.
     */
    public function execute(UpdateLeadData $data): Lead
    {
        return $this->repository->update($data);
    }
}
