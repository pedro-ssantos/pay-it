<?php

namespace AppModules\User\Models;

use Illuminate\Database\Eloquent\Builder;
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

    protected $attributes = [
        'type' => 'common_user',
    ];

    public static function booted(): void
    {
        parent::booted();

        static::addGlobalScope('common', function (Builder $builder) {
            $builder->where('type', 'common_user');
        });
    }
}
