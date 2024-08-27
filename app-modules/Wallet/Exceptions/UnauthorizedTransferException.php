<?php

namespace AppModules\Wallet\Exceptions;

class UnauthorizedTransferException extends \Exception
{
    protected $message = 'Unauthorized transfer';
}