<?php

namespace AppModules\Wallet\Services\Interfaces;

use AppModules\User\Models\User;

interface WalletCreatorInterface
{
    public function createWalletForUser(User $user): void;
}
