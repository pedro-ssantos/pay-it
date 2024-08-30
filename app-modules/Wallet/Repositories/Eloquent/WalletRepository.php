<?php

namespace AppModules\Wallet\Repositories\Eloquent;

use AppModules\Wallet\Models\Wallet;
use AppModules\Wallet\Exceptions\WalletNotFoundException;
use AppModules\Wallet\Repositories\Interfaces\WalletRepositoryInterface;

class WalletRepository implements WalletRepositoryInterface
{
    public function findByUserIdAndLock(int $userId): ?Wallet
    {
        $wallet = Wallet::where('user_id', $userId)->lockForUpdate()->first();

        if (!$wallet) {
            throw new WalletNotFoundException();
        }

        return $wallet;
    }
}
