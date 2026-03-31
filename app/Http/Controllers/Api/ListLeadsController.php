<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Actions\ListLeadsAction;
use App\Http\Requests\ListLeadsRequest;
use App\Http\Resources\LeadResource;
use App\Http\Responses\ApiResponse;
use Illuminate\Http\JsonResponse;

class ListLeadsController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(ListLeadsRequest $request, ListLeadsAction $action): JsonResponse
    {
        $leads = $action->execute($request->validated());

        return ApiResponse::success(
            message: 'Leads retrieved successfully.',
            data: LeadResource::collection($leads)->response()->getData(true)
        );
    }
}
