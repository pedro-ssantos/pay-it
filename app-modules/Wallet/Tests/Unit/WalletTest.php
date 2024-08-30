<?php

namespace AppModules\User\Tests\Unit;

use AppModules\User\Models\User;
use AppModules\Wallet\Models\Wallet;
use AppModules\Wallet\Tests\WalletTestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class WalletTest extends WalletTestCase
{
    public function test_wallet_belongs_to_user()
    {
        $user = $this->createUser();
        $wallet = $this->createWallet($user->id);

        $this->assertInstanceOf(User::class, $wallet->user);
        $this->assertEquals($user->id, $wallet->user->id);
    }
}
