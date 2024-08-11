<?php

namespace AppModules\Notification\Providers;

use GuzzleHttp\Client;
use Illuminate\Support\ServiceProvider;
use AppModules\Notification\Services\SmsNotificationStrategy;
use AppModules\Notification\Services\EmailNotificationStrategy;
use AppModules\Notification\Services\Factories\NotificationStrategyFactory;
use AppModules\Notification\Services\Decorators\RetryableNotificationStrategy;
use AppModules\Notification\Services\Interfaces\NotificationStrategyInterface;

class NotificationServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        // Register SmsNotificationStrategy in the service container
        $this->app->singleton(SmsNotificationStrategy::class, function ($app) {
            return new SmsNotificationStrategy($app->make(Client::class));
        });

        // Register EmailNotificationStrategy in the service container
        $this->app->singleton(EmailNotificationStrategy::class, function ($app) {
            return new EmailNotificationStrategy($app->make(Client::class));
        });

        // Register RetryableNotificationStrategy in the service container
        $this->app->singleton(RetryableNotificationStrategy::class, function ($app) {
            return new RetryableNotificationStrategy($app->make(Client::class));
        });
        // Registrar NotificationStrategyFactory no service container
        $this->app->singleton(NotificationStrategyFactory::class, function ($app) {
            return new NotificationStrategyFactory(
                $app->make(SmsNotificationStrategy::class),
                $app->make(EmailNotificationStrategy::class),
                $app->make(RetryableNotificationStrategy::class)
            );
        });
    }
}
