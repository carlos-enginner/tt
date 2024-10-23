<?php

namespace App\Services\Payment;

use App\Models\PaymentOrders;

class PaymentOrderService
{
    protected $newRow = [];

    public function buildRow(int $purchaseOrderId, $paymentOrder)
    {
        $this->newRow['purchase_orders_id'] = $purchaseOrderId;
        $this->newRow["payment_transaction_metadata"] = json_encode($paymentOrder);

        return $this;
    }

    public function store()
    {
        return PaymentOrders::create($this->newRow);
    }
}
