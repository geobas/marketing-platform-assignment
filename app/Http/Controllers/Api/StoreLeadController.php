<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Actions\StoreLeadAction;
use App\Http\Requests\StoreLeadRequest;
use App\Http\Responses\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class StoreLeadController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(StoreLeadRequest $request, StoreLeadAction $action): JsonResponse
    {
        $action->execute($request->toDto());

        Log::info('Lead stored successfully.', ['lead' => $request->toDto()->toArray()]);

        return ApiResponse::success(
            message: 'Lead stored successfully.',
            statusCode: Response::HTTP_CREATED
        );
    }
}
