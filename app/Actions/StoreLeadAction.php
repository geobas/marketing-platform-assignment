<?php

declare(strict_types=1);

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
        private readonly LeadRepositoryInterface $repository
    ) {}

    /**
     * Execute the action to store a new Lead.
     */
    public function execute(LeadData $data): Lead
    {
        return $this->repository->create($data);
    }
}
