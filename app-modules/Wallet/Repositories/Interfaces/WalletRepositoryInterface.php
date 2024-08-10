<?php

namespace AppModules\Wallet\Repositories\Interfaces;

use AppModules\Wallet\Models\Wallet;

interface WalletRepositoryInterface
{
    public function findByUserId(int $userId): ?Wallet;
    public function decreaseBalance(int $userId, float $amount): void;
    public function increaseBalance(int $userId, float $amount): void;
}
