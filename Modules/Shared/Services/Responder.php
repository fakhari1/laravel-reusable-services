<?php

namespace Modules\Shared\Services;

use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class Responder
{
    public static function success(
        $data = null,
        string $message = '',
        int $statusCode = Response::HTTP_OK
    ): JsonResponse
    {
        return response()->json([
            'status_code' => $statusCode,
            'success' => true,
            'message' => empty($message) ? trans('container.operation_was_completed_successfully') : $message,
            'data' => $data,
        ], $statusCode);
    }

    public static function error(
        string $message = 'Error occurred',
               $errors = null,
        int    $statusCode = Response::HTTP_BAD_REQUEST
    ): JsonResponse
    {
        return response()->json([
            'status_code' => $statusCode,
            'success' => false,
            'message' => $message,
            'errors' => $errors,
        ], $statusCode);
    }
}
