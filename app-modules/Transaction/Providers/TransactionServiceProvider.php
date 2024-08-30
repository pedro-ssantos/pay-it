<?php

namespace AppModules\Transaction\Providers;

use Illuminate\Support\ServiceProvider;
use AppModules\Transaction\Services\TransferService;
use AppModules\Transaction\Services\TrasnferStrategy\TransferStrategyFactory;
use AppModules\User\Database\Repositories\Interfaces\UserRepositoryInterface;
use AppModules\Authorization\Services\Interfaces\AuthorizationServiceInterface;
use AppModules\Transaction\Services\TrasnferStrategy\Interface\TransferStrategyInterface;

class TransactionServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register() {}

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot(): void
    {
        $this->loadMigrationsFrom(__DIR__ . '/../Database/Migrations');
        $this->app->bind(TransferStrategyInterface::class, function ($app) {
            return new TransferService(
                $app->make(TransferStrategyFactory::class),
                $app->make(AuthorizationServiceInterface::class),
                $app->make(UserRepositoryInterface::class)
            );
        });
    }
}
