<?php

namespace AppModules\Wallet\Repositories\Eloquent;

use AppModules\Wallet\Models\Wallet;
use AppModules\Wallet\Repositories\Interfaces\WalletRepositoryInterface;

class WalletRepository implements WalletRepositoryInterface
{
    public function findByUserIdAndLock(int $userId): ?Wallet
    {
        return Wallet::where('user_id', $userId)->lockForUpdate()->first();
    }
}
