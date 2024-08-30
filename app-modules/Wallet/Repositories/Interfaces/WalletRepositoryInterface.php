<?php

namespace AppModules\Wallet\Repositories\Interfaces;

use AppModules\Wallet\Models\Wallet;

interface WalletRepositoryInterface
{
    public function findByUserIdAndLock(int $userId): ?Wallet;
}
