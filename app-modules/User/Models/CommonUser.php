<?php

namespace AppModules\User\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class CommonUser extends User
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'password',
        'cpf',
    ];

    protected $visible = [
        'type'
    ];
}
