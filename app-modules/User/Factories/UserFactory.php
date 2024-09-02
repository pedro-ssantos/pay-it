<?php

namespace AppModules\User\Factories;

use Illuminate\Support\Arr;
use AppModules\Wallet\Models\Wallet;
use AppModules\User\Database\Repositories\Interfaces\CommonUserRepositoryInterface;
use AppModules\User\Database\Repositories\Interfaces\MerchantUserRepositoryInterface;
use AppModules\Wallet\Services\Interfaces\WalletCreatorInterface;

class UserFactory
{

    public function __construct(
        protected CommonUserRepositoryInterface $commonUserRepository,
        protected MerchantUserRepositoryInterface $merchantUserRepository,
        protected WalletCreatorInterface $walletCreator
    ) {}

    public function create(array $data)
    {
        if (Arr::has($data, 'cpf')) {
            return $this->createCommonUser($data);
        }

        if (Arr::has($data, 'cnpj')) {
            return $this->createMerchantUser($data);
        }

        throw new \InvalidArgumentException('CPF or CNPJ is required.');
    }

    protected function createCommonUser(array $data)
    {
        $user = $this->commonUserRepository->create($data);
        $this->walletCreator->createWalletForUser($user);
        return $user;
    }

    protected function createMerchantUser(array $data)
    {
        $user = $this->merchantUserRepository->create($data);
        $this->walletCreator->createWalletForUser($user);
        return $user;
    }
}
