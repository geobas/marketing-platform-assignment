<?php

declare(strict_types=1);

namespace App\Actions;

use App\Contracts\LeadRepositoryInterface;
use App\Models\Lead;
use Illuminate\Pagination\LengthAwarePaginator;

class ListLeadsAction
{
    /**
     * Constructor
     */
    public function __construct(
        private readonly LeadRepositoryInterface $repository
    ) {}

    /**
     * Execute the action to list all Leads.
     *
     * @param array<string, mixed> $data
     * @return LengthAwarePaginator<int, Lead>
     */
    public function execute(array $data): LengthAwarePaginator
    {
        return $this->repository->list($data);
    }
}
