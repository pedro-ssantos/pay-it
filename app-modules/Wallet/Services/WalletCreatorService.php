<?php

namespace AppModules\Wallet\Services;

use AppModules\User\Models\User;
use AppModules\Wallet\Models\Wallet;
use AppModules\Wallet\Services\Interfaces\WalletCreatorInterface;

class WalletCreatorService implements WalletCreatorInterface
{
    public function createWalletForUser(User $user): void
    {
        $wallet = new Wallet();
        $wallet->user_id = $user->id;
        $wallet->balance = 0.00;
        $wallet->save();
    }
}
