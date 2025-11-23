<?php

namespace App\Http\Controllers\Api;

use App\Actions\ListLeadsAction;
use App\Http\Requests\ListLeadsRequest;
use App\Http\Resources\LeadResource;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class ListLeadsController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(ListLeadsRequest $request, ListLeadsAction $action): AnonymousResourceCollection
    {
        $leads = $action->execute($request->validated());

        return LeadResource::collection($leads);
    }
}
