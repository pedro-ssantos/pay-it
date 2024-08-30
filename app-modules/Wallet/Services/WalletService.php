<?php

namespace AppModules\Wallet\Services;

use Illuminate\Support\Facades\DB;
use AppModules\Wallet\Models\Wallet;
use AppModules\Wallet\Services\Interfaces\WalletServiceInterface;
use AppModules\Wallet\Repositories\Interfaces\WalletRepositoryInterface;

class WalletService implements WalletServiceInterface
{

    public function __construct(protected WalletRepositoryInterface $walletRepository) {}

    public function decreaseBalance(Wallet $wallet, float $amount): void
    {
        if ($wallet) {
            $wallet->balance -= $amount;
            $wallet->save();
        }
    }

    public function increaseBalance(Wallet $wallet, float $amount): void
    {
        if ($wallet) {
            $wallet->balance += $amount;
            $wallet->save();
        }
    }
}
