<?php

namespace AppModules\Wallet\Services\Interfaces;

use AppModules\User\Models\User;

interface TransferServiceInterface
{
    public function transfer(User $senderId, User $receiverId, float $amount): bool;
}
