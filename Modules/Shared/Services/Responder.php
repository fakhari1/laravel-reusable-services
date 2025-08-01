<?php

namespace Modules\Modules\Shared\Services;

use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use function Modules\Shared\Services\response;
use function Modules\Shared\Services\trans;

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

    public static function validationError(
        $errors,
        string $message = 'Validation failed'
    ): JsonResponse
    {
        return response()->json([
            'success' => false,
            'message' => $message,
            'errors' => $errors
        ], Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    public static function unauthorizedError(
        string $message = 'Unauthorized'
    ): JsonResponse
    {
        return response()->json([
            'success' => false,
            'message' => $message,
        ], Response::HTTP_UNAUTHORIZED);
    }

    public static function forbiddenError(
        string $message = 'Forbidden'
    ): JsonResponse
    {
        return response()->json([
            'success' => false,
            'message' => $message,
        ], Response::HTTP_FORBIDDEN);
    }

    public static function notFound(
        string $message = 'Resource not found'
    ): JsonResponse
    {
        return response()->json([
            'success' => false,
            'message' => $message,
            'errors' => null
        ], Response::HTTP_NOT_FOUND);
    }
}
