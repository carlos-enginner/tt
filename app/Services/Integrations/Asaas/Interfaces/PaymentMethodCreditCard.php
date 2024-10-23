<?php

namespace App\Services\Integrations\Asaas\Interfaces;

interface PaymentMethodCreditCard
{
    public function pay($paymentId, $paymentMetadata);
}