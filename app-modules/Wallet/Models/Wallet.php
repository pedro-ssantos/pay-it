<?php

namespace AppModules\Wallet\Models;

use AppModules\User\Models\User;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Wallet extends Authenticatable
{
    protected $fillable = ['user_id', 'balance'];

    /**
     * Define o relacionamento entre a Wallet e o User.
     *
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class,  'user_id');
    }
}
