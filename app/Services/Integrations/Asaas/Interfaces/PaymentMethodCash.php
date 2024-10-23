<?php

namespace App\Services\Integrations\Asaas\Interfaces;

interface PaymentMethodCash
{
    public function pay($chargeOrder);
}
