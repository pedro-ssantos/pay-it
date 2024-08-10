<?php

namespace AppModules\User\Database\Repositories\Eloquent;

use AppModules\User\Models\MerchantUser;
use AppModules\User\Database\Repositories\Interfaces\MerchantUserRepositoryInterface;


class MerchantUserRepository implements MerchantUserRepositoryInterface
{
    public function create(array $data)
    {
        return MerchantUser::create($data);
    }
}
