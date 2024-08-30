<?php

namespace AppModules\Wallet\Services\Interfaces;

use AppModules\Wallet\Models\Wallet;

interface BalanceValidatorInterface
{
    /**
     * Validates if the wallet has sufficient balance.
     *
     * @param Wallet $wallet
     * @param float $amount
     * @return bool
     * @throws InsufficientFundsException if the balance is insufficient
     */
    public function validate(Wallet $wallet, float $amount): bool;
}
