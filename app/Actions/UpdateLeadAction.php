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
     * Execute the action to store a new Lead.
     */
    public function execute(UpdateLeadData $data): bool
    {
        return $this->repository->update($data);
    }
}
