<?php

namespace App\Http\Controllers\Api;

use App\Actions\ListLeadsAction;
use App\Http\Requests\ListLeadsRequest;
use App\Http\Resources\LeadResource;

class ListLeadsController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(ListLeadsRequest $request, ListLeadsAction $action)
    {
        $leads = $action->execute($request->validated());

        return LeadResource::collection($leads);
    }
}
