<?php

namespace App\Services\Purchase;

use App\Models\PurchaseOrders;

class PurchaseOrderService
{
    public function store(array $data)
    {
        return PurchaseOrders::create($data);
    }
}
