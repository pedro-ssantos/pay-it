<?php

namespace AppModules\User\Database\Repositories\Interfaces;

use AppModules\User\Models\CommonUser;
use AppModules\User\Models\MerchantUser;


interface UserRepositoryInterface
{
    /**
     * Find a user by email.
     *
     * @param string $email
     * @return CommonUser|MerchantUser|null
     */
    public function findByEmail(string $email);
}
