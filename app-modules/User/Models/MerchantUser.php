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

    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->type = 'merchant_user';
        });
    }
}
