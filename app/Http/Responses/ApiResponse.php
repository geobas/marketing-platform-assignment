<?php

declare(strict_types=1);

namespace App\Http\Responses;

use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class ApiResponse
{
    /**
     * Return a standardized success response.
     */
    public static function success(
        string $message = 'Success',
        mixed $data = [],
        int $statusCode = Response::HTTP_OK
    ): JsonResponse {
        $payload = [
            'status' => 'success',
            'message' => $message,
        ];

        // If data is a Resource Collection, convert it to an array
        if (is_object($data) && method_exists($data, 'response')) {
            $data = $data->response()->getData(true);
        }

        // If the array has a 'data' key (typical of Laravel Resources/Paginators)
        // we "flatten" it so 'links' and 'meta' sit at the top level
        if (is_array($data) && isset($data['data'])) {
            $payload['data'] = $data['data'];

            if (isset($data['links'])) {
                $payload['links'] = $data['links'];
            }

            if (isset($data['meta'])) {
                $payload['meta'] = $data['meta'];
            }
        } else {
            $payload['data'] = $data;
        }

        return response()->json($payload, $statusCode);
    }

    /**
     * Return a standardized error response.
     */
    public static function error(
        string $message = 'An error occurred',
        int $statusCode = Response::HTTP_BAD_REQUEST,
        mixed $errors = null
    ): JsonResponse {
        return response()->json([
            'status' => 'error',
            'message' => $message,
            'errors' => $errors,
        ], $statusCode);
    }
}
