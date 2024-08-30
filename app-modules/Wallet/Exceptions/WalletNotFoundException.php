<?php

namespace AppModules\Wallet\Exceptions;

use Exception;

class WalletNotFoundException extends Exception
{
    protected $message = 'Wallet not found for the given user ID.';
}
