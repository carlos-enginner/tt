<?php

namespace App\Http\Controllers\Purchase;

use App\Http\Controllers\Controller;
use App\Jobs\Purchase\PurchaseOrder;
use App\Models\PurchaseOrders;
use App\Traits\ApiResponse;
use DateTime;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PurchaseController extends Controller
{
    use ApiResponse;

    public function newOrder(Request $request): JsonResponse
    {
        try {

            $rules = [
                'consumer_name' => 'required|string|max:255',
                'consumer_id' => 'required|string|max:11',
                'purchase_description' => 'required|string|max:255',
                'purchase_value' => 'required|numeric|min:0',
                'payment_method' => 'required|in:CREDIT_CARD,PIX,BOLETO,UNDEFINED',
            ];

            if ($request->input('payment_method') === 'CREDIT_CARD') {
                $rules['payment_metadata.creditcard_holdername'] = 'required|string|max:255';
                $rules['payment_metadata.creditcard_number'] = 'required|string|size:16';
                $rules['payment_metadata.creditcard_validate'] = 'required|date_format:m/y';
                $rules['payment_metadata.creditcard_cvv'] = 'required|string|size:3';
            }

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                \Log::info($validator->errors());
                $errors = $validator->errors()->all();
                $errorMessages = implode(", ", $errors);
                return $this->error("purchase_fields_failed", $errorMessages, null, 422);
            }

            $validatedPurchaseData = $validator->validated();

            if ($request->input('payment_method') === 'CREDIT_CARD') {
                $dateTime = DateTime::createFromFormat('m/y', $validatedPurchaseData["payment_metadata"]["creditcard_validate"]);
                $validatedPurchaseData["payment_metadata"]["creditcard_validate"] = $dateTime->format('Y-m');
            } else {
                $validatedPurchaseData['payment_metadata'] = [];
            }

            PurchaseOrder::dispatch($validatedPurchaseData)->onQueue('purchase_order');

            return $this->success(
                [],
                "Obrigado! Sua compra foi realizada e será processada em breve.",
                200
            );
        } catch (Exception $e) {
            \Log::info($e->getTraceAsString());
            return $this->error(['message' => $e->getMessage()], 200);
        }
    }

    /**
     * Retorna um registro de compra pelo ID.
     *
     * @param  int  $id
     * @return JsonResponse
     */
    public function getOrder($id): JsonResponse
    {
        $purchase = PurchaseOrders::find($id);

        if (!$purchase) {
            return response()->json(['message' => 'Purchase not found.'], 404);
        }

        return response()->json($purchase, 200);
    }
}
