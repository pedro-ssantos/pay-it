<?php
namespace AppModules\Wallet\Exceptions;

class InsufficientFundsException extends \Exception
{
    protected $message = 'Insufficient funds';
}