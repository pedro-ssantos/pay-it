<?php

namespace AppModules\Wallet\Services;

use AppModules\Wallet\Repositories\Interfaces\WalletRepositoryInterface;
use AppModules\Wallet\Services\Interfaces\BalanceValidatorInterface;

class BalanceValidator implements BalanceValidatorInterface
{

    public function __construct(protected WalletRepositoryInterface $walletRepository) {}

    public function validate(int $userId, float $amount): bool
    {
        $wallet = $this->walletRepository->findByUserId($userId);
        return $wallet && $wallet->balance >= $amount;
    }
}
