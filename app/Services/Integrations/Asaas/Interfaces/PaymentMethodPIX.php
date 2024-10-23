<?php

namespace App\Services\Integrations\Asaas\Interfaces;

interface PaymentMethodPIX
{
    public function pay($chargeOrder);
}
