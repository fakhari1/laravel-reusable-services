<?php

namespace Modules\Shared\Services;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\File\Exception\AccessDeniedException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

/**
 *
 */
class ExceptionHandler
{
    public function handle($exception)
    {

        return match (true) {
            $exception instanceof ValidationException => $this->handleValidationException($exception),
            $exception instanceof ModelNotFoundException => $this->handleModelNotFoundException($exception),
            $exception instanceof AuthenticationException => $this->handleAuthenticationException($exception),

            // Authorization exceptions (most specific first)
            $exception instanceof AuthorizationException => $this->handleAuthorizationException($exception),
            $exception instanceof AccessDeniedException => $this->handleAuthorizationException($exception),

            // Database exceptions
            $exception instanceof QueryException => $this->handleQueryException($exception),

            // HTTP exceptions (specific before generic)
            $exception instanceof NotFoundHttpException => $this->handleNotFoundHttpException($exception),
            $exception instanceof MethodNotAllowedHttpException => $this->handleMethodNotAllowedException($exception),
            $exception instanceof HttpException => $this->handleHttpException($exception), // Generic HTTP - goes last

            default => $this->handleGenericException($exception),
        };

    }

    private function handleValidationException(ValidationException $exception)
    {
        return Responder::error(trans('container.the_given_data_is_invalid'), $exception->errors(), Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    private function handleGenericException(Throwable $exception)
    {
        $statusCode = method_exists($exception, 'getStatusCode') ? $exception->getStatusCode() : Response::HTTP_INTERNAL_SERVER_ERROR;

        return Responder::error('An internal server error has occurred.', [
            $exception->getMessage(),
            ...$exception->getTrace()
        ], $statusCode);
    }

    private function handleModelNotFoundException(ModelNotFoundException $exception)
    {
        $model = class_basename($exception->getModel());

        return Responder::error("The requested {$model} was not found", 'Resource not found', Response::HTTP_NOT_FOUND);
    }

    private function handleAuthenticationException(AuthenticationException $exception)
    {
        return Responder::error('Authentication is required to access this resource', $exception->getMessage(), Response::HTTP_UNAUTHORIZED);
    }

    private function handleAuthorizationException(AuthorizationException|AccessDeniedException $exception)
    {
        return Responder::error('You do not have permission to access this resource', $exception->getMessage(), Response::HTTP_FORBIDDEN);
    }

    private function handleQueryException(QueryException $exception)
    {
        return Responder::error($exception->getMessage(), $exception->getTrace(), Response::HTTP_INTERNAL_SERVER_ERROR);
    }

    private function handleNotFoundHttpException(NotFoundHttpException $exception)
    {
        return Responder::error('The requested resource was not found: ' . $exception->getMessage(), $exception->getTrace(), Response::HTTP_NOT_FOUND);
    }

    private function handleMethodNotAllowedException(MethodNotAllowedHttpException $exception)
    {
        return Responder::error('The HTTP method is not allowed for this route: ' . $exception->getMessage(), $exception->getTrace(), Response::HTTP_METHOD_NOT_ALLOWED);
    }

    private function handleHttpException(HttpException $exception)
    {
        return Responder::error($exception->getMessage(), $exception->getTrace(), $exception->getStatusCode());
    }


}
