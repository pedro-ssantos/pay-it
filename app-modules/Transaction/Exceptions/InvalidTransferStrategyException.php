<?php

namespace AppModules\Transaction\Exceptions;

class InvalidTransferStrategyException extends \Exception
{
    protected $message = 'Invalid transfer strategy.';
}
