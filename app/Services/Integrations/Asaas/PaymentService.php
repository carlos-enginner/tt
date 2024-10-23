<?php

namespace App\Services\Integrations\Asaas;

use App\Exceptions\PaymentGenericErrorException;
use App\Exceptions\PaymentNotReceivedException;
use App\Services\Integrations\Asaas\Methods\BankSlipPayment;
use App\Services\Integrations\Asaas\Methods\CashPayment;
use App\Services\Integrations\Asaas\Methods\CreditCardPayment;
use App\Services\Integrations\Asaas\Methods\PixPayment;
use Throwable;

class PaymentService
{
    public $paymentMethods;
    private $httpRequestService;

    public function __construct(HttpRequestService $httpRequestService)
    {
        $this->paymentMethods = [
            'CREDIT_CARD' => new CreditCardPayment(),
            'PIX' => new PixPayment(),
            'BOLETO' => new BankSlipPayment(),
            'UNDEFINED' => new CashPayment(),
        ];

        $this->httpRequestService = $httpRequestService;
    }

    public function getPurchaseOrder($purchaseId)
    {
        $this->httpRequestService->setBasePath(env("LOCALHOST_PURCHASE_API_URL"));

        $response = $this->httpRequestService->request("get", "orders/{$purchaseId}");
        $paymentMetadata = json_decode($response["payment_metadata"] ?: []);
        $chargeMedata = json_decode($response["charge_transaction_metadata"]);

        return [
            'payment_metadata' => $paymentMetadata,
            'charge_medata' => $chargeMedata
        ];
    }

    public function pay($purchaseOrder)
    {
        try {
            $chargeOrder = $purchaseOrder['charge_medata'];
            $paymentMetadata = $purchaseOrder['payment_metadata'] ?: [];

            if (!isset($this->paymentMethods[$chargeOrder->billingType])) {
                throw new PaymentGenericErrorException("Método de pagamento não identificado");
            }
            return $this->paymentMethods[$chargeOrder->billingType]->pay($chargeOrder, $paymentMetadata);
        } catch (Throwable $e) {
            throw new PaymentNotReceivedException(
                "Não foi possível processar o pagamento <(0_0)>."
            );
        }
    }
}
