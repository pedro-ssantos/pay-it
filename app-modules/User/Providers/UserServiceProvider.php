<?php

namespace AppModules\User\Providers;

use Illuminate\Support\ServiceProvider;
use AppModules\User\Factories\UserFactory;
use AppModules\User\Database\Repositories\Eloquent\CommonUserRepository;
use AppModules\User\Database\Repositories\Eloquent\MerchantUserRepository;
use AppModules\User\Database\Repositories\Interfaces\CommonUserRepositoryInterface;
use AppModules\User\Database\Repositories\Interfaces\MerchantUserRepositoryInterface;


class UserServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(CommonUserRepositoryInterface::class, CommonUserRepository::class);
        $this->app->singleton(MerchantUserRepositoryInterface::class, MerchantUserRepository::class);
        $this->app->singleton(UserFactory::class, function ($app) {
            return new UserFactory(
                $app->make(CommonUserRepositoryInterface::class),
                $app->make(MerchantUserRepositoryInterface::class)
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
