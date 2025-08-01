<?php

namespace Modules\Modules\Shared\Services;

use Illuminate\Http\Client\PendingRequest;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;
use function Modules\Shared\Services\trans;

class HttpRequestHandler
{
    protected $baseUrl;
    protected $timeout = 0;
    protected $headers = [];
    protected $retryTimes = 1;
    protected $retryDelay = 0;

    public function __construct()
    {

    }

    public function to(string $baseUrl): self
    {
        $this->baseUrl = $baseUrl;
        return $this;
    }

    public function withTimeout(int $timeout): self
    {
        $this->timeout = $timeout;
        return $this;
    }

    public function withHeaders(array $headers): self
    {
        $this->headers = array_merge($this->headers, $headers);
        return $this;
    }

    public function withBearerToken(string $token): self
    {
        $this->headers['Authorization'] = 'Bearer ' . $token;
        return $this;
    }

    public function build(): PendingRequest
    {
        return Http::timeout($this->timeout)->withHeaders($this->headers)->retry($this->retryTimes, $this->retryDelay)
            ->when(!empty($this->baseUrl), function (PendingRequest $request) {
                return $request->baseUrl($this->baseUrl);
            });
    }

    public function get(string $endpoint, array $queryParams = [])
    {
        try {
            $response = $this->build()->get($endpoint, $queryParams);
            return $this->responder($response, 'GET', $endpoint);
        } catch (\Exception $ex) {
            return $this->exception($ex, 'GET', $endpoint);
        }
    }

    public function post(string $endpoint, array $data = [], array $queryParams = []): array
    {
        try {
            $req = $this->build();

            if (!empty($queryParams)) {
                $endpoint .= '?' . http_build_query($queryParams);
            }

            $response = $req->post($endpoint, $data);

            return $this->responder($response, 'POST', $endpoint);
        } catch (\Exception $ex) {
            return $this->exception($ex, 'POST', $endpoint);
        }
    }

    public function responder(Response $response, string $method, string $endpoint): array
    {
        $statusCode = $response->status();
        $responseData = $response->json() ?? [];

        if ($response->successful()) {
            return [
                'success' => true,
                'status_code' => $statusCode,
                'data' => $responseData,
                'headers' => $response->headers(),
                trans('container.operation_was_completed_successfully')
            ];
        }

        return [
            'success' => false,
            'status_code' => $statusCode,
            'data' => $responseData,
            'headers' => $response->headers(),
            'message' => $this->getErrorMessage($statusCode, $responseData),
            'error' => true
        ];
    }

    protected function exception($ex, string $method, string $endpoint)
    {
        return [
            'success' => false,
            'status_code' => $ex->getCode() ?? 0,
            'data' => null,
            'message' => 'Request failed: ' . $ex->getMessage(),
            'errors' => $ex->getTrace(),
            'error' => true,
            'exception' => $ex->getMessage()
        ];
    }

    protected function getErrorMessage(int $statusCode, array $responseData): string
    {
        if (isset($responseData['message'])) {
            return $responseData['message'];
        }

        return match ($statusCode) {
            400 => 'Bad Request',
            401 => 'Unauthorized',
            403 => 'Forbidden',
            404 => 'Not Found',
            422 => 'Validation Error',
            429 => 'Too Many Requests',
            500 => 'Internal Server Error',
            502 => 'Bad Gateway',
            503 => 'Service Unavailable',
            default => 'Unknown Error: ' . $statusCode,
        };
    }
}
