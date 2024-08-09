<?php

namespace AppModules\User\Models;

class CommonUser extends User
{
    protected $fillable = [
        'name',
        'email',
        'password',
        'cpf',
    ];
}
