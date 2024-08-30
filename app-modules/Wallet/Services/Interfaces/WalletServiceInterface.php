<?php

namespace AppModules\Wallet\Services\Interfaces;

use AppModules\Wallet\Models\Wallet;

interface WalletServiceInterface
{
    public function decreaseBalance(Wallet $wallet, float $amount): void;
    public function increaseBalance(Wallet $wallet, float $amount): void;
}
