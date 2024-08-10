<?php

namespace AppModules\Wallet\Services\Interfaces;

interface BalanceValidatorInterface
{
    public function validate(int $userId, float $amount): bool;
}
