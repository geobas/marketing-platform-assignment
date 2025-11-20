<?php

namespace App\Http\Controllers\Api;

use App\Actions\UpdateLeadAction;
use App\Http\Requests\UpdateLeadRequest;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class UpdateLeadController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(UpdateLeadRequest $request, string $id, UpdateLeadAction $action)
    {
        $action->execute($request->toDto());

        Log::info('Lead updated successfully.', ['lead' => $request->toDto()->toArray()]);

        return response()->json(['message' => 'Lead updated successfully.'], Response::HTTP_OK);
    }
}
