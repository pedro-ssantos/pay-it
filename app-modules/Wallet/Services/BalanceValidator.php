<?php

namespace AppModules\Wallet\Services;

use AppModules\Wallet\Models\Wallet;
use AppModules\Wallet\Exceptions\InsufficientFundsException;
use AppModules\Wallet\Services\Interfaces\BalanceValidatorInterface;
use AppModules\Wallet\Repositories\Interfaces\WalletRepositoryInterface;

class BalanceValidator implements BalanceValidatorInterface
{
    /**
     * Validates if the wallet has sufficient balance.
     *
     * @param Wallet $wallet
     * @param float $amount
     * @return bool
     * @throws InsufficientFundsException if the balance is insufficient
     */
    public function validate(Wallet $wallet, float $amount): bool
    {
        return $wallet->balance >= $amount ? true : throw new InsufficientFundsException('Insufficient balance');;
    }
}
