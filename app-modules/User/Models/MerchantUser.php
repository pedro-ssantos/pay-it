<?php

namespace AppModules\User\Models;

class MerchantUser extends User
{
    protected $fillable = [
        'name',
        'email',
        'password',
        'cnpj',
    ];
}
