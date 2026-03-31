<?php

declare(strict_types=1);

namespace App\Exceptions;

use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use ReflectionClass;

class LeadRepositoryException extends Exception
{
    /**
     * Create a new LeadRepositoryException instance.
     */
    public function __construct(
        string $message = 'Failed to save or update lead',
        public readonly string $internalMessage = '',
        public readonly int $status = 500,
    ) {
        parent::__construct($message, $status);
    }

    /**
     * If the request expects JSON, returns a JSON response with the error message
     * and the HTTP status code defined in the exception. Otherwise, redirects back
     * to the previous page with a flash error message.
     */
    public function render(Request $request): JsonResponse|RedirectResponse
    {
        if ($request->expectsJson()) {
            return response()->json([
                'error' => $this->getMessage(),
            ], $this->status);
        }

        return back()->with('error', 'Failed to save or update lead');
    }

    /**
     * Report the exception.
     */
    public function report(): void
    {
        Log::error((new ReflectionClass($this))->getShortName() . ': ' . $this->internalMessage);
    }
}
