<?php

namespace AppModules\Wallet\Providers;

use Illuminate\Support\ServiceProvider;
use AppModules\Wallet\Services\TransferService;
use AppModules\Wallet\Services\BalanceValidator;
use AppModules\Wallet\Services\TransferStrategyFactory;
use AppModules\Wallet\Repositories\Eloquent\WalletRepository;
use AppModules\Wallet\Services\Interfaces\TransferServiceInterface;
use AppModules\Wallet\Services\Interfaces\BalanceValidatorInterface;
use AppModules\Wallet\Repositories\Interfaces\WalletRepositoryInterface;
use AppModules\Authorization\Services\Interfaces\AuthorizationServiceInterface;

class WalletServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(WalletRepositoryInterface::class, WalletRepository::class);
        $this->app->bind(BalanceValidatorInterface::class, BalanceValidator::class);
        $this->app->singleton(TransferService::class, function ($app) {
            return new TransferService(
                $app->make(TransferStrategyFactory::class),
                $app->make(AuthorizationServiceInterface::class)
            );
        });
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot(): void
    {
        $this->loadMigrationsFrom(__DIR__ . '/../Database/Migrations');
    }
}
