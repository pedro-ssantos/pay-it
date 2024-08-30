<?php

namespace AppModules\Transaction\Services\TrasnferStrategy;

use AppModules\User\Models\User;
use AppModules\User\Models\CommonUser;
use AppModules\User\Models\MerchantUser;
use AppModules\Wallet\Services\Interfaces\WalletServiceInterface;
use AppModules\Wallet\Services\Interfaces\BalanceValidatorInterface;
use AppModules\Transaction\Exceptions\InvalidTransferStrategyException;
use AppModules\Wallet\Repositories\Interfaces\WalletRepositoryInterface;
use AppModules\Transaction\Services\TrasnferStrategy\CommonToCommonTransferStrategy;
use AppModules\Transaction\Services\TrasnferStrategy\CommonToMerchantTransferStrategy;
use AppModules\Transaction\Services\TrasnferStrategy\Interface\TransferStrategyInterface;

class TransferStrategyFactory
{
    public function make(User $sender, User $receiver): TransferStrategyInterface
    {
        if ($sender instanceof CommonUser && $receiver instanceof MerchantUser) {
            return new CommonToMerchantTransferStrategy(
                app()->make(WalletServiceInterface::class),
                app()->make(WalletRepositoryInterface::class),
                app()->make(BalanceValidatorInterface::class),
            );
        } elseif ($sender instanceof CommonUser && $receiver instanceof CommonUser) {
            return new CommonToCommonTransferStrategy(
                app()->make(WalletServiceInterface::class),
                app()->make(WalletRepositoryInterface::class),
                app()->make(BalanceValidatorInterface::class),
            );
        }

        throw new InvalidTransferStrategyException();
    }
}
