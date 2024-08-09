<?php

namespace AppModules\User\Factories;

use Illuminate\Support\Arr;
use AppModules\User\Models\CommonUser;
use AppModules\User\Models\MerchantUser;

class UserFactory
{
    public static function create(array $data)
    {
        if (Arr::has($data, 'cpf')) {
            return self::createCommonUser($data);
        }

        if (Arr::has($data, 'cnpj')) {
            return self::createMerchantUser($data);
        }

        throw new \InvalidArgumentException('CPF or CNPJ is required.');
    }

    protected static function createCommonUser(array $data)
    {
        return CommonUser::create($data);
    }

    protected static function createMerchantUser(array $data)
    {
        return MerchantUser::create($data);
    }
}
