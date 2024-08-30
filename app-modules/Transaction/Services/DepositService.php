<?php

namespace AppModules\Transaction\Services;

use Illuminate\Support\Facades\DB;
use AppModules\Wallet\Services\Interfaces\WalletServiceInterface;
use AppModules\Wallet\Repositories\Interfaces\WalletRepositoryInterface;

class DepositService
{

    public function __construct(
        protected WalletRepositoryInterface $walletRepository,
        protected WalletServiceInterface $walletService
    ) {}

    public function execute(int $receiverId, float $amount): void
    {
        DB::transaction(function () use ($receiverId, $amount) {
            $receiverWallet = $this->walletRepository->findByUserIdAndLock($receiverId);

            $this->walletService->increaseBalance($receiverWallet, $amount);
        });
    }
}
