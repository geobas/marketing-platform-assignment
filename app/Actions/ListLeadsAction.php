<?php

namespace App\Actions;

use App\Contracts\LeadRepositoryInterface;
use Illuminate\Pagination\LengthAwarePaginator;

class ListLeadsAction
{
    /**
     * Constructor
     */
    public function __construct(
        private LeadRepositoryInterface $repository
    ) {}

    /**
     * Execute the action to list all Leads.
     */
    public function execute(array $data): LengthAwarePaginator
    {
        return $this->repository->list($data);
    }
}
