<?php

namespace AppModules\Authorization\Providers;

use GuzzleHttp\Client;
use Illuminate\Support\ServiceProvider;
use AppModules\Authorization\Services\ExternalAuthorizationService;
use AppModules\Authorization\Services\Interfaces\AuthorizationServiceInterface;

class AuthorizationServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton(AuthorizationServiceInterface::class, function ($app) {
            return new ExternalAuthorizationService(new Client());
        });
    }
}
