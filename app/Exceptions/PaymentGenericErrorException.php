<?php

namespace App\Exceptions;

use Exception;

class PaymentGenericErrorException extends Exception
{
    public function __construct($message)
    {
        parent::__construct($message);
    }
}
