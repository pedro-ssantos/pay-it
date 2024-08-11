<?php

namespace AppModules\Notification\Services\Interfaces;

interface NotificationStrategyInterface
{
    public function send(string $recipient, string $message): bool;
}
