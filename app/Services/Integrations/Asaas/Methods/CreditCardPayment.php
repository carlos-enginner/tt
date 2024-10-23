<?php

namespace App\Services\Integrations\Asaas\Methods;

use App\Services\Integrations\Asaas\HttpRequestService;
use App\Services\Integrations\Asaas\Interfaces\PaymentMethodCreditCard;

class CreditCardPayment implements PaymentMethodCreditCard
{
    protected $httpService;

    public function __construct()
    {
        $this->httpService = new HttpRequestService();
    }

    public function pay($chargeOrder, $paymentMetadata)
    {
        \Log::info('PAYING::CreditCardPayment');
        $paymentId = $chargeOrder->id;
        $holderName = $paymentMetadata->creditcard_holdername;

        $this->httpService->setBasePath(env("ASAAS_API_URL",null));
        return $this->httpService->request("post", "payments/{$paymentId}/payWithCreditCard/", [
            'creditCard' => [
                'holderName' => $holderName,
                'number' => $paymentMetadata->creditcard_number,
                'expiryMonth' => explode("-", $paymentMetadata->creditcard_validate)[1],
                'expiryYear' => explode("-", $paymentMetadata->creditcard_validate)[0],
                'ccv' => $paymentMetadata->creditcard_cvv,
            ],
            'creditCardHolderInfo' => [
                'name' => 'bladasilva',
                'email' => 'blabla@bla.com.br',
                'cpfCnpj' => '12345678909',
                'postalCode' => '74825020',
                'addressNumber' => '00',
                'addressComplement' => '00',
                'phone' => '629921254554',
                'mobilePhone' => '629921254554',
            ],
        ]);
    }
}
