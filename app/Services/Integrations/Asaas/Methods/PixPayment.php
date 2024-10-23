<?php

namespace App\Services\Integrations\Asaas\Methods;

use App\Services\Integrations\Asaas\HttpRequestService;
use App\Services\Integrations\Asaas\Interfaces\PaymentMethodPIX;

class PixPayment implements PaymentMethodPIX
{
    protected $httpService;

    public function __construct()
    {
        $this->httpService = new HttpRequestService();
    }

    public function pay($chargeOrder)
    {
        \Log::info('PAYING::PixPayment');

        $payloadPix = $chargeOrder->pixQrcodeGenerated->payload;
        $value = $chargeOrder->value;

        $this->httpService->setBasePath(env("ASAAS_API_URL", null));
        return $this->httpService->request("post", "pix/qrCodes/pay", [
            'qrCode' => ['payload' => $payloadPix],
            'value' => $value,
        ]);
    }
}
