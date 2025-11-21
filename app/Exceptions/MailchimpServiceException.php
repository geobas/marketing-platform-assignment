<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use ReflectionClass;

class MailchimpServiceException extends Exception
{
    /**
     * Constructor
     */
    public function __construct(
        string $message = 'Mailchimp service error',
        public readonly string $internalMessage = '',
        public readonly int $status = 500,
    ) {
        parent::__construct($message, $status);
    }

    /**
     * Render the exception as a JSON response.
     */
    public function render(Request $request): JsonResponse
    {
        return response()->json([
            'error' => $this->getMessage(),
        ], $this->status);
    }

    /**
     * Report the exception.
     */
    public function report(): void
    {
        Log::error((new ReflectionClass($this))->getShortName() . ': ' . $this->internalMessage);
    }
}
