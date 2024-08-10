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

class CommonToCommonTransferStrategy implements TransferServiceInterface
{

    public function __construct(
        protected WalletRepositoryInterface $walletRepository,
        protected BalanceValidatorInterface $balanceValidator
    ) {}

    public function transfer(User $sender, User $receiver, float $amount): bool
    {
        return DB::transaction(function () use ($sender, $receiver, $amount) {

            $senderWallet = Wallet::where('user_id', $sender->id)->lockForUpdate()->first();
            $receiverWallet = Wallet::where('user_id', $receiver->id)->lockForUpdate()->first();

            if (!$this->balanceValidator->validate($sender->id, $amount)) {
                throw new Exception('Insufficient balance');
            }

            $this->walletRepository->decreaseBalance($sender->id, $amount);
            $this->walletRepository->increaseBalance($receiver->id, $amount);

            Transaction::create([
                'sender_id' => $sender->id,
                'receiver_id' => $receiver->id,
                'amount' => $amount,
            ]);

            return true;
        });
    }
}
