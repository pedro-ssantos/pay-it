<?php

namespace AppModules\Wallet\Services;

use AppModules\User\Models\User;
use AppModules\User\Models\CommonUser;
use AppModules\User\Models\MerchantUser;
use AppModules\Wallet\Services\CommonToCommonTransferStrategy;
use AppModules\Wallet\Services\CommonToMerchantTransferStrategy;
use AppModules\Wallet\Services\Interfaces\TransferServiceInterface;

class TransferStrategyFactory
{
    public function make(User $sender, User $receiver): TransferServiceInterface
    {
        if ($sender instanceof CommonUser && $receiver instanceof MerchantUser) {
            return new CommonToMerchantTransferStrategy(
                app()->make('AppModules\Wallet\Repositories\Interfaces\WalletRepositoryInterface'),
                app()->make('AppModules\Wallet\Services\Interfaces\BalanceValidatorInterface')
            );
        } elseif ($sender instanceof CommonUser && $receiver instanceof CommonUser) {
            return new CommonToCommonTransferStrategy(
                app()->make('AppModules\Wallet\Repositories\Interfaces\WalletRepositoryInterface'),
                app()->make('AppModules\Wallet\Services\Interfaces\BalanceValidatorInterface'),
            );
        }

        throw new \Exception('No valid strategy found for this transfer.');
    }
}
