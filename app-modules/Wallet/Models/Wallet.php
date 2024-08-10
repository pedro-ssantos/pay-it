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

    /**
     * Incrementa o saldo da carteira.
     *
     * @param float $amount
     * @return void
     */
    public function increaseBalance(float $amount): void
    {
        $this->balance += $amount;
        $this->save();
    }

    /**
     * Decrementa o saldo da carteira.
     *
     * @param float $amount
     * @return void
     */
    public function decreaseBalance(float $amount): void
    {
        $this->balance -= $amount;
        $this->save();
    }

    /**
     * Verifica se a carteira possui saldo suficiente.
     *
     * @param float $amount
     * @return bool
     */
    public function hasSufficientBalance(float $amount): bool
    {
        return $this->balance >= $amount;
    }
}
