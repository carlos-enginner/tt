<?php

namespace App\Services\Integrations\Asaas\Methods;

use App\Services\Integrations\Asaas\HttpRequestService;
use App\Services\Integrations\Asaas\Interfaces\PaymentMethodCash;

class CashPayment implements PaymentMethodCash
{
    protected $httpService;

    public function __construct()
    {
        $this->httpService = new HttpRequestService();
    }

    public function pay($chargeOrder)
    {
        \Log::info('PAYING:: CashPayment');
        $this->httpService->setBasePath(env("ASAAS_API_URL",null));
        return $this->httpService->request("post", "payments/{$chargeOrder->id}/receiveInCash", [
            'paymentDate' => now()->format('Y-m-d'),
            'value' => $chargeOrder->value,
        ]);
    }
}
