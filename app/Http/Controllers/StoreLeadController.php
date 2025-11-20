<?php

namespace App\Http\Controllers;

use App\Actions\StoreLeadAction;
use App\Http\Requests\StoreLeadRequest;
use Symfony\Component\HttpFoundation\Response;

class StoreLeadController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(StoreLeadRequest $request, StoreLeadAction $action)
    {
        $action->execute($request->toDto());

        return response()->json(['message' => 'Lead saved successfully.'], Response::HTTP_CREATED);
    }
}
