<?php

namespace AppModules\User\Tests\Unit;

use Tests\TestCase;
use AppModules\User\Models\CommonUser;
use AppModules\User\Models\MerchantUser;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_creates_a_common_user_instance()
    {
        $user = new CommonUser([
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'password' => bcrypt('secret'),
            'cpf' => '12345678901'
        ]);

        $this->assertInstanceOf(CommonUser::class, $user);
        $this->assertEquals('12345678901', $user->cpf);

    }

    public function test_it_creates_a_merchant_user_instance()
    {
        $user = new MerchantUser([
            'name' => 'Jane Doe',
            'email' => 'jane@example.com',
            'password' => bcrypt('secret'),
            'cnpj' => '12345678901234'
        ]);

        $this->assertInstanceOf(MerchantUser::class, $user);
        $this->assertEquals('12345678901234', $user->cnpj);
    }
}
