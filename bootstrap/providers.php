<?php

return [
    App\Providers\AppServiceProvider::class,
    AppModules\User\Providers\UserServiceProvider::class,
    AppModules\Wallet\Providers\WalletServiceProvider::class,
    AppModules\Authorization\Providers\AuthorizationServiceProvider::class,
];
