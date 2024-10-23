<?php

namespace App\Exceptions;

use Exception;

class PaymentNotReceivedException extends Exception
{
    public function __construct($message)
    {
        parent::__construct($message);
    }
}
