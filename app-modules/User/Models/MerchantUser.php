<?php

namespace AppModules\User\Models;

use AppModules\User\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MerchantUser extends User
{
    use HasFactory;

    protected $table = 'users';

    protected $fillable = [
        'name',
        'email',
        'password',
        'cnpj',
    ];

    protected $attributes = [
        'type' => 'merchant_user',
    ];

    public static function booted(): void
    {
        parent::booted();

        static::addGlobalScope('merchant', function (Builder $builder) {
            $builder->where('type', 'merchant_user');
        });
    }
}
