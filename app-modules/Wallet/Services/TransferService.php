<?php

namespace AppModules\Wallet\Services;

use Exception;
use AppModules\User\Models\User;
use AppModules\Notification\Jobs\SendNotificationJob;
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

        $this->strategy->transfer($sender, $receiver, $amount);

        //Se a transferência falhar será lançada uma exceção e a notificação não será enviada
        SendNotificationJob::dispatch($sender, $receiver, $amount);

        return true;
    }
}
