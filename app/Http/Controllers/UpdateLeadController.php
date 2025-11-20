<?php

namespace App\Http\Controllers;

use App\Actions\UpdateLeadAction;
use App\Http\Requests\UpdateLeadRequest;
use Symfony\Component\HttpFoundation\Response;

class UpdateLeadController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(UpdateLeadRequest $request, string $id, UpdateLeadAction $action)
    {
        $action->execute($request->toDto());

        return response()->json(['message' => 'Lead updated successfully.'], Response::HTTP_OK);
    }
}
