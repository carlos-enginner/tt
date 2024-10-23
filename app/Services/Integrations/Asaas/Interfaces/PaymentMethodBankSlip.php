<?php

namespace App\Services\Integrations\Asaas\Interfaces;

interface PaymentMethodBankSlip
{
    public function pay($chargeOrder);
}
