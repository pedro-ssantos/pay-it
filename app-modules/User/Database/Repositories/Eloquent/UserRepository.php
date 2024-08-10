<?php

namespace AppModules\User\Database\Repositories\Eloquent;

use AppModules\User\Models\CommonUser;
use AppModules\User\Models\MerchantUser;
use AppModules\User\Database\Repositories\Interfaces\UserRepositoryInterface;


class UserRepository implements UserRepositoryInterface
{
    public function findByEmail(string $email)
    {
        $user = CommonUser::where('email', $email)->first();
        if ($user) {
            return $user;
        }

        $user = MerchantUser::where('email', $email)->first();
        if ($user) {
            return $user;
        }

        return null;
    }
}
