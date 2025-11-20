<?php
namespace App\Actions;

use App\DTOs\LeadData;
use App\Contracts\LeadRepositoryInterface;
use App\Models\Lead;

class StoreLeadAction
{
    /**
     * Constructor
     * 
     * @param LeadRepositoryInterface $repository
     */
    public function __construct(
        private LeadRepositoryInterface $repository
    ) {}

    /**
     * Execute the action to store a new Lead.
     * 
     * @param LeadData $data
     * @return Lead
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
