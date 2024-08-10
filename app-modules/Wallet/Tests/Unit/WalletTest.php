<?php

namespace AppModules\User\Tests\Unit;

use AppModules\User\Models\User;
use AppModules\Wallet\Models\Wallet;
use AppModules\Wallet\Tests\WalletTestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class WalletTest extends WalletTestCase
{
    use RefreshDatabase;

    public function test_wallet_belongs_to_user()
    {
        $user = $this->createUser();
        $wallet = $this->createWallet($user->id);

        $this->assertInstanceOf(User::class, $wallet->user);
        $this->assertEquals($user->id, $wallet->user->id);
    }

    public function test_it_can_increase_balance()
    {
        $user = $this->createUser();
        $wallet = $this->createWallet($user->id, 100);

        $wallet->increaseBalance(50);

        $this->assertEquals(150, $wallet->balance);
    }

    public function test_it_can_decrease_balance()
    {
        $user = $this->createUser();
        $wallet = $this->createWallet($user->id, 100);

        $wallet->decreaseBalance(40);

        $this->assertEquals(60, $wallet->balance);
    }

    public function test_it_checks_sufficient_balance()
    {
        $user = $this->createUser();
        $wallet = $this->createWallet($user->id, 100);

        $this->assertTrue($wallet->hasSufficientBalance(50));
        $this->assertFalse($wallet->hasSufficientBalance(150));
    }
}
