<?php

namespace AppModules\User\Database\Repositories\Eloquent;

use AppModules\User\Models\CommonUser;
use AppModules\User\Database\Repositories\Interfaces\CommonUserRepositoryInterface;

class CommonUserRepository implements CommonUserRepositoryInterface
{
    public function create(array $data)
    {
        return CommonUser::create($data);
    }
}
