<?php

namespace AppModules\Wallet\Services;

use AppModules\User\Models\User;
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
        if (!$this->balanceValidator->validate($sender->id, $amount)) {
            return false;
        }

        $this->walletRepository->decreaseBalance($sender->id, $amount);
        $this->walletRepository->increaseBalance($receiver->id, $amount);

        return true;
    }
}
