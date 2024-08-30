<?php

namespace AppModules\Transaction\Exceptions;

class InsufficientFundsException extends \Exception
{
    protected $message = 'Insufficient funds';
}
