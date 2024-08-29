<?php

namespace AppModules\Wallet\Services;

use AppModules\User\Models\User;
use Illuminate\Support\Facades\Auth;
use AppModules\Notification\Jobs\SendNotificationJob;
use AppModules\Wallet\Exceptions\UnauthorizedTransferException;
use AppModules\User\Database\Repositories\Eloquent\UserRepository;
use AppModules\Wallet\Services\Interfaces\TransferServiceInterface;
use AppModules\Authorization\Services\Interfaces\AuthorizationServiceInterface;

class TransferService
{
    protected TransferServiceInterface $strategy;

    public function __construct(
        protected TransferStrategyFactory $transferStrategyFactory,
        protected AuthorizationServiceInterface $authorizationService,
        protected UserRepository $userRepository
    ) {}

    public function execute(int $idSender, int $idReceiver, float $amount): bool
    {
        if (!$this->authorizationService->authorize()) {
            throw new UnauthorizedTransferException();
        }
        // Remetente não pode ser diferente do usuário autenticado, pois só pode transferir seu próprio dinheiro
        if (Auth::id() !== $idSender) {
            throw new UnauthorizedTransferException();
        }

        $sender = $this->userRepository->findById($idSender);
        $receiver = $this->userRepository->findById($idReceiver);

        $this->strategy = $this->transferStrategyFactory->make($sender, $receiver);

        $this->strategy->transfer($sender, $receiver, $amount);

        //Se a transferência falhar será lançada uma exceção e a notificação não será enviada
        SendNotificationJob::dispatch($sender, $receiver, $amount);

        return true;
    }
}
