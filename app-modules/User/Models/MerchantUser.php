<?php

namespace AppModules\User\Models;

use AppModules\User\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MerchantUser extends User
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'password',
        'cnpj',
    ];
}
