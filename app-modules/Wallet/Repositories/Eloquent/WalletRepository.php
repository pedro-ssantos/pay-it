<?php

namespace AppModules\Wallet\Repositories\Eloquent;

use AppModules\Wallet\Models\Wallet;
use AppModules\Wallet\Repositories\Interfaces\WalletRepositoryInterface;

class WalletRepository implements WalletRepositoryInterface
{
    public function findByUserId(int $userId): ?Wallet
    {
        return Wallet::where('user_id', $userId)->first();
    }

    public function decreaseBalance(int $userId, float $amount): void
    {
        $wallet = $this->findByUserId($userId);
        $wallet->balance -= $amount;
        $wallet->save();
    }

    public function increaseBalance(int $userId, float $amount): void
    {
        $wallet = $this->findByUserId($userId);
        $wallet->balance += $amount;
        $wallet->save();
    }
}
