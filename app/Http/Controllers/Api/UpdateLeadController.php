<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Actions\UpdateLeadAction;
use App\Http\Requests\UpdateLeadRequest;
use App\Http\Responses\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class UpdateLeadController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(UpdateLeadRequest $request, string $id, UpdateLeadAction $action): JsonResponse
    {
        $action->execute($request->toDto());

        Log::info('Lead updated successfully.', ['lead' => $request->toDto()->toArray()]);

        return ApiResponse::success(
            message: 'Lead updated successfully.',
            statusCode: Response::HTTP_OK
        );
    }
}
