<?php

namespace AppModules\Transaction\Services\TrasnferStrategy;

use AppModules\User\Models\User;
use Illuminate\Support\Facades\DB;
use AppModules\Transaction\Models\Transaction;
use AppModules\Wallet\Services\Interfaces\WalletServiceInterface;
use AppModules\Wallet\Services\Interfaces\BalanceValidatorInterface;
use AppModules\Wallet\Repositories\Interfaces\WalletRepositoryInterface;
use AppModules\Transaction\Services\TrasnferStrategy\Interface\TransferStrategyInterface;

class CommonToCommonTransferStrategy implements TransferStrategyInterface
{
    protected const TRANSACTION_TIMEOUT = 5;

    public function __construct(
        protected WalletServiceInterface $walletService,
        protected WalletRepositoryInterface $walletRepository,
        protected BalanceValidatorInterface $balanceValidator
    ) {}

    public function transfer(User $sender, User $receiver, float $amount): bool
    {
        return DB::transaction(function () use ($sender, $receiver, $amount) {
            [$senderWallet, $receiverWallet] = $this->getOrderedWallets($sender, $receiver);

            $this->balanceValidator->validate($senderWallet, $amount);

            $this->walletService->decreaseBalance($senderWallet, $amount);
            $this->walletService->increaseBalance($receiverWallet, $amount);

            $this->createTransaction($sender, $receiver, $amount);

            return true;
        }, self::TRANSACTION_TIMEOUT);
    }

    /**
     * acquire wallet in order by id.
     * prevents deadlock
     */
    private function getOrderedWallets(User $sender, User $receiver): array
    {
        $users = [$sender, $receiver];
        usort($users, fn($a, $b) => $a->id <=> $b->id);

        $firstWallet = $this->walletRepository->findByUserIdAndLock($users[0]->id);
        $secondWallet = $this->walletRepository->findByUserIdAndLock($users[1]->id);

        $senderWallet = $users[0]->id === $sender->id ? $firstWallet : $secondWallet;
        $receiverWallet = $users[0]->id === $sender->id ? $secondWallet : $firstWallet;

        return [$senderWallet, $receiverWallet];
    }

    private function createTransaction(User $sender, User $receiver, float $amount): void
    {
        Transaction::create([
            'sender_id' => $sender->id,
            'receiver_id' => $receiver->id,
            'amount' => $amount,
        ]);
    }
}
