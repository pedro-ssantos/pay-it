<?php

namespace AppModules\User\Database\Repositories\Eloquent;

use AppModules\User\Models\CommonUser;
use AppModules\User\Models\MerchantUser;
use AppModules\User\Database\Repositories\Interfaces\UserRepositoryInterface;
use AppModules\User\Models\User;

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

    public function findById(int $id): User
    {
        $user = CommonUser::where('id', $id)->first();
        if ($user) {
            return $user;
        }

        $user = MerchantUser::where('id', $id)->first();
        if ($user) {
            return $user;
        }

        return null;
    }
}
