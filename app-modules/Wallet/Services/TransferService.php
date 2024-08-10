<?php

namespace AppModules\Wallet\Services;

use AppModules\User\Models\User;
use AppModules\Wallet\Services\Interfaces\TransferServiceInterface;

class TransferService
{
    protected TransferServiceInterface $strategy;

    public function __construct(protected TransferStrategyFactory $transferStrategyFactory) {}

    public function execute(User $sender, User $receiver, float $amount): bool
    {
        $this->strategy = $this->transferStrategyFactory->make($sender, $receiver);

        return $this->strategy->transfer($sender, $receiver, $amount);
    }
}
