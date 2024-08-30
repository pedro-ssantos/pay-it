<?php

namespace AppModules\Transaction\Exceptions;


class UnauthorizedTransferException extends \Exception
{
    protected $message = 'Unauthorized transfer';
}
