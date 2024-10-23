<?php

namespace App\Services\Integrations\Asaas;

use App\Exceptions\PaymentGenericErrorException;
use App\Services\Integrations\Asaas\Interfaces\HttpRequestInterface;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class HttpRequestService implements HttpRequestInterface
{
    private $defaultHeaders;
    protected $basePath;

    public function getBasePath()
    {
        return $this->basePath;
    }

    public function setBasePath($basePath)
    {
        $this->basePath = $basePath;
    }

    public function getDefaultHeaders()
    {
        return $this->defaultHeaders;
    }

    public function __construct()
    {
        $accessToken = env("ASAAS_API_TOKEN", null);

        if (empty($accessToken)) {
            throw new PaymentGenericErrorException(
                "API Token da Asaas não foi inserido"
            );
        }

        $this->defaultHeaders = [
            'Accept' => 'application/json',
            'access_token' => $accessToken,
            'Content-Type' => 'application/json',
        ];
    }

    public function request($method, $url, $data = [], $headers = [])
    {
        $basePath = $this->getBasePath();

        Log::info("request");
        Log::info($basePath);

        if (empty($basePath)) {
            throw new PaymentGenericErrorException(
                "Endpoint da API Asaas não foi configurado"
            );
        }

        Log::info($method);
        Log::info($basePath . $url);
        Log::info($data);
        $response = Http::withHeaders(
            array_merge($this->defaultHeaders, $headers)
        )->{$method}($basePath . $url, $data);

        Log::info($response);

        return $this->handleResponse($response);
    }

    public function handleResponse($response)
    {
        $data = $response->json();

        if (!$response->successful() && empty($data["errors"])) {
            throw new PaymentGenericErrorException('Serviço indisponível.');
        }

        if (!empty($data["errors"])) {
            throw new PaymentGenericErrorException(json_encode($data["errors"][0]));
        }

        return $data;
    }
}
