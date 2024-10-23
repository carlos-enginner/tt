<?php

namespace App\Services\Integrations\Asaas;

use DomainException;
use Illuminate\Support\Facades\Http;
use LogicException;

class ChargeService
{
    private function generateQrCode($paymentId)
    {
        $response = Http::withHeaders([
            'accept' => 'application/json',
            'access_token' => env('ASAAS_API_TOKEN', null),
            'content-type' => 'application/json',
        ])->get("https://sandbox.asaas.com/api/v3/payments/$paymentId/pixQrCode", []);

        if (!$response->successful() && empty($response["errors"])) {
            throw new LogicException(
                json_encode(
                    [
                        'code' => 'service_unavailable',
                        'description' => 'Serviço indisponível, não foi possível processar a requisição'
                    ]
                )
            );
        }

        $response = $response->json();

        if (!empty($response["errors"])) {
            throw new DomainException(
                json_encode($response["errors"][0])
            );
        }

        return $response;
    }

    private function generateBankSlipIdentificationField($paymentId)
    {
        $response = Http::withHeaders([
            'accept' => 'application/json',
            'access_token' => env('ASAAS_API_TOKEN', null),
            'content-type' => 'application/json',
        ])->get("https://sandbox.asaas.com/api/v3/payments/$paymentId/identificationField", []);

        if (!$response->successful() && empty($response["errors"])) {
            throw new LogicException(
                json_encode(
                    [
                        'code' => 'service_unavailable',
                        'description' => 'Serviço indisponível, não foi possível processar a requisição'
                    ]
                )
            );
        }

        $response = $response->json();

        if (!empty($response["errors"])) {
            throw new DomainException(
                json_encode($response["errors"][0])
            );
        }

        return $response;
    }

    public function generate(array $data, $customerId)
    {
        $response = Http::withHeaders([
            'accept' => 'application/json',
            'access_token' => env('ASAAS_API_TOKEN', null),
            'content-type' => 'application/json',
        ])->post('https://sandbox.asaas.com/api/v3/payments', [
            'billingType' => $data["payment_method"],
            'customer' => $customerId,
            'description' => $data['purchase_description'],
            'value' => $data["purchase_value"],
            'dueDate' => date("Y-m-d")
        ]);

        if (!$response->successful() && empty($response["errors"])) {
            throw new LogicException(
                json_encode(
                    [
                        'code' => 'service_unavailable',
                        'description' => 'Serviço indisponível, não foi possível processar a requisição'
                    ]
                )
            );
        }

        $response = $response->json();

        if (!empty($response["errors"])) {
            throw new DomainException(
                json_encode($response["errors"][0])
            );
        }

        if (empty($response["id"])) {
            throw new LogicException(json_encode(
                [
                    'code' => 'invalid_customer',
                    'description' => 'id do cliente inválido'
                ]
            ));
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
