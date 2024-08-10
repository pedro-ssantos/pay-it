<?php

namespace AppModules\User\Database\Factories;

use Illuminate\Support\Facades\Hash;
use AppModules\User\Models\CommonUser;
use Illuminate\Database\Eloquent\Factories\Factory;

class CommonUserFactory extends Factory
{
    protected $model = CommonUser::class;

    public function definition()
    {
        return [
            'name' => $this->faker->name,
            'email' => $this->faker->unique()->safeEmail,
            'password' => Hash::make('password'), // senha padrão para testes
            'cpf' => $this->faker->cpf(false), // usando faker-br para gerar CPF
            'type' => 'common_user',
        ];
    }
}
