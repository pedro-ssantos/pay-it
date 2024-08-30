<?php

namespace AppModules\Transaction\Services\TrasnferStrategy\Interface;

use AppModules\User\Models\User;

interface TransferStrategyInterface
{
    public function transfer(User $senderId, User $receiverId, float $amount): bool;
}
