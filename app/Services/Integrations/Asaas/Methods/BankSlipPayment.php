<?php

namespace App\Services\Integrations\Asaas\Methods;

use App\Services\Integrations\Asaas\HttpRequestService;
use App\Services\Integrations\Asaas\Interfaces\PaymentMethodBankSlip;

class BankSlipPayment implements PaymentMethodBankSlip
{
    protected $httpService;

    public function __construct()
    {
        $this->httpService = new HttpRequestService();
    }
    public function pay($chargeOrder)
    {
        \Log::info('PAYING:: BankSlipPayment');
        $this->httpService->setBasePath(env("ASAAS_API_URL",null));
        return $this->httpService->request("post", "bill", [
            'identificationField' => $chargeOrder->bankSlipIdentificationField->identificationField,
            'scheduleDate' => now()->format('Y-m-d'),
            'description' => 'Pgto fake boleto',
            'value' => $chargeOrder->value,
        ]);
    }
}
