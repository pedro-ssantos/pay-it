<?php

namespace AppModules\Notification\Services\Factories;

use GuzzleHttp\Client;
use AppModules\Notification\Services\SmsNotificationStrategy;
use AppModules\Notification\Services\EmailNotificationStrategy;
use AppModules\Notification\Services\Decorators\RetryableNotificationStrategy;
use AppModules\Notification\Services\Interfaces\NotificationStrategyInterface;

class NotificationStrategyFactory
{
    public static function make(string $type): NotificationStrategyInterface
    {
        $client = new Client();

        switch ($type) {
            case 'email':
                $strategy = new EmailNotificationStrategy($client);
                break;
            case 'sms':
                $strategy = new SmsNotificationStrategy($client);
                break;
            default:
                throw new \InvalidArgumentException("Notification type $type not supported.");
        }

        return new RetryableNotificationStrategy($strategy);
    }
}
