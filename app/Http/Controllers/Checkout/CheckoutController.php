<?php

namespace App\Http\Controllers\Checkout;

use App\Http\Controllers\Controller;
use App\Traits\ApiResponse;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Http;
use LogicException;
use Throwable;

class CheckoutController extends Controller
{
    use ApiResponse;

    public function index()
    {
        return view('checkout.index');
    }

    public function create()
    {
        return view('checkout.create');
    }

    public function process(Request $request)
    {
        try {

            $checkoutData = $request->all();

            if ($request->input('payment_method') === 'CREDIT_CARD') {
                $checkoutData['payment_metadata']['creditcard_holdername'] = Arr::get($checkoutData, 'creditcard_holdername');
                $checkoutData['payment_metadata']['creditcard_number'] = Arr::get($checkoutData, 'creditcard_number');
                $checkoutData['payment_metadata']['creditcard_validate'] = Arr::get($checkoutData, 'creditcard_validate');
                $checkoutData['payment_metadata']['creditcard_cvv'] = Arr::get($checkoutData, 'creditcard_cvv');
                unset(
                    $checkoutData['creditcard_holdername'],
                    $checkoutData['creditcard_number'],
                    $checkoutData['creditcard_validate'],
                    $checkoutData['creditcard_cvv']
                );
            }

            $response = Http::withHeaders([
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
            ])->post('http://localhost/purchase/orders', $checkoutData);


            \Log::info($response);

            if (!$response->successful() && $response->getStatusCode() === 500) {
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

            $error = Arr::get($response, "error", null);

            if (!empty($error)) {
                return $response;
            }

            return $this->success($response, 'Solicitação enviada com sucesso');
        } catch (Throwable $e) {
            return $this->error('error', $e?->getMessage());
        }
    }

    public function status()
    {
        return view('checkout.status');
    }

    public function preview(Request $request)
    {
        $paymentMethod = $request->get("paymentMethod");

        return view('checkout.preview', ["paymentMethod" => $paymentMethod]);
    }
}
