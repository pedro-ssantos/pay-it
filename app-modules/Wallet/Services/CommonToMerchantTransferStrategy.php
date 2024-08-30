<?php

namespace AppModules\Wallet\Services;

use Exception;

use AppModules\User\Models\User;
use Illuminate\Support\Facades\DB;
use AppModules\Wallet\Models\Wallet;
use AppModules\Wallet\Models\Transaction;
use AppModules\Wallet\Services\Interfaces\TransferServiceInterface;
use AppModules\Wallet\Services\Interfaces\BalanceValidatorInterface;
use AppModules\Wallet\Repositories\Interfaces\WalletRepositoryInterface;

class CommonToMerchantTransferStrategy implements TransferServiceInterface
{
    public function __construct(
        protected WalletService $walletService,
        protected WalletRepositoryInterface $walletRepository,
        protected BalanceValidatorInterface $balanceValidator
    ) {}

    public function transfer(User $sender, User $receiver, float $amount): bool
    {
        return DB::transaction(function () use ($sender, $receiver, $amount) {

            $senderWallet = $this->walletRepository->findByUserIdAndLock($sender->id);
            $receiverWallet = $this->walletRepository->findByUserIdAndLock($receiver->id);

            $this->balanceValidator->validate($senderWallet, $amount);

            $this->walletService->decreaseBalance($senderWallet, $amount);
            $this->walletService->increaseBalance($receiverWallet, $amount);

            Transaction::create([
                'sender_id' => $sender->id,
                'receiver_id' => $receiver->id,
                'amount' => $amount,
            ]);

            return true;
        });
    }
}
