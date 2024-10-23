<?php

namespace App\Exceptions;

use Exception;

class PaymentNotEnabledException extends Exception
{
    public function __construct($paymentMethod)
    {
        $message = "Assim que você tiver sua conta aprovada, você poderá utilizar o {$paymentMethod} no Asaas..";

        parent::__construct($message);
    }
}
