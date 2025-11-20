<?php

namespace App\Http\Controllers\Api;

use App\Actions\StoreLeadAction;
use App\Http\Requests\StoreLeadRequest;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class StoreLeadController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(StoreLeadRequest $request, StoreLeadAction $action)
    {
        $action->execute($request->toDto());

        Log::info('Lead stored successfully.', ['lead' => $request->toDto()->toArray()]);

        return response()->json(['message' => 'Lead stored successfully.'], Response::HTTP_CREATED);
    }
}
