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

    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->type = 'common_user';
        });
    }
}
