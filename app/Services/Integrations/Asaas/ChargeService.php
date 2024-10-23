<?php

namespace App\Services\Integrations\Asaas;

use App\Services\Integrations\Asaas\HttpRequestService;
use Illuminate\Support\Facades\Log;
use LogicException;

class ChargeService
{
    private $httpRequestService;

    public function __construct(HttpRequestService $httpRequestService)
    {
        $this->httpRequestService = $httpRequestService;
    }

    private function generateQrCode($paymentId)
    {
        return $this->httpRequestService->request('get', "payments/$paymentId/pixQrCode");
    }

    private function generateBankSlipIdentificationField($paymentId)
    {
        return $this->httpRequestService->request('get', "payments/$paymentId/identificationField");
    }

    public function generate(array $data, $customerId)
    {
        Log::info('CHARGING:: ChargeService');
        $this->httpRequestService->setBasePath(env("ASAAS_API_URL", null));
        $response = $this->httpRequestService->request('post', 'payments', [
            'billingType' => $data["payment_method"],
            'customer' => $customerId,
            'description' => $data['purchase_description'],
            'value' => $data["purchase_value"],
            'dueDate' => date("Y-m-d"),
        ]);

        if (empty($response["id"])) {
            throw new LogicException(json_encode([
                'code' => 'invalid_customer',
                'description' => 'id do cliente inválido'
            ]));
        }

        if ($response['billingType'] === 'PIX') {
            $response["pixQrcodeGenerated"] = $this->generateQrCode($response["id"]);
        }

        if ($response['billingType'] === 'BOLETO') {
            $response["bankSlipIdentificationField"] = $this->generateBankSlipIdentificationField($response["id"]);
        }

        return $response;
    }
}
