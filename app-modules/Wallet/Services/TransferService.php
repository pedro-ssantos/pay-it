<?php

namespace AppModules\Wallet\Services;

use Exception;
use AppModules\User\Models\User;
use AppModules\Wallet\Services\Interfaces\TransferServiceInterface;
use AppModules\Authorization\Services\Interfaces\AuthorizationServiceInterface;

class TransferService
{
    protected TransferServiceInterface $strategy;

    public function __construct(
        protected TransferStrategyFactory $transferStrategyFactory,
        protected AuthorizationServiceInterface $authorizationService,
    ) {}

    public function execute(User $sender, User $receiver, float $amount): bool
    {
        if (!$this->authorizationService->authorize()) {
            throw new Exception('Unauthorized');
        }

        $this->strategy = $this->transferStrategyFactory->make($sender, $receiver);

        return $this->strategy->transfer($sender, $receiver, $amount);
    }
}
