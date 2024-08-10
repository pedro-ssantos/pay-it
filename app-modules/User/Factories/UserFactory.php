<?php

namespace AppModules\User\Factories;

use Illuminate\Support\Arr;
use AppModules\User\Database\Repositories\Interfaces\CommonUserRepositoryInterface;
use AppModules\User\Database\Repositories\Interfaces\MerchantUserRepositoryInterface;

class UserFactory
{
    protected $commonUserRepository;
    protected $merchantUserRepository;

    public function __construct(
        CommonUserRepositoryInterface $commonUserRepository,
        MerchantUserRepositoryInterface $merchantUserRepository
    ) {
        $this->commonUserRepository = $commonUserRepository;
        $this->merchantUserRepository = $merchantUserRepository;
    }

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
        return $this->commonUserRepository->create($data);
    }

    protected function createMerchantUser(array $data)
    {
        return $this->merchantUserRepository->create($data);
    }
}
