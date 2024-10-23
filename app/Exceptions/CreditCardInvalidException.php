<?php

namespace App\Exceptions;

use Exception;

class CreditCardInvalidException extends Exception
{
    public function __construct()
    {
        $message = "Cartão de crédito inválido";

        parent::__construct($message);
    }
}
