<?php

namespace App\Services\Integrations\Asaas;

use App\Services\Integrations\Asaas\HttpRequestService;
use Illuminate\Support\Facades\Log;
use LogicException;

class CustomerService
{
    private $httpRequestService;

    public function __construct(HttpRequestService $httpRequestService)
    {
        $this->httpRequestService = $httpRequestService;
    }

    public function save(array $data)
    {
        Log::info('CREATING CONSUMER::CustomerService');
        $this->httpRequestService->setBasePath(env("ASAAS_API_URL", null));
        $response = $this->httpRequestService->request('post', 'customers', [
            'name' => $data["consumer_name"],
            'cpfCnpj' => $data["consumer_id"],
        ]);

        if (empty($response["id"])) {
            throw new LogicException(json_encode([
                'code' => 'invalid_customer',
                'description' => 'id do cliente inválido'
            ]));
        }

        return $response;
    }
}
