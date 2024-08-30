<?php

namespace AppModules\Wallet\Tests;

use Tests\TestCase;
use AppModules\User\Models\User;
use AppModules\Wallet\Models\Wallet;
use AppModules\User\Models\CommonUser;
use AppModules\User\Models\MerchantUser;
use Illuminate\Foundation\Testing\RefreshDatabase;

class WalletTestCase extends TestCase
{
    use RefreshDatabase;

    private static $emailCounter = 0;
    private static $cpfCounter = 10000000000;
    private static $cnpjCounter = 10000000000000;

    public function createUser($type = 'common')
    {
        self::$emailCounter++;
        $email = 'test' . self::$emailCounter . '@example.com';

        if ($type === 'common') {
            $user = new CommonUser();
            $user->cpf = self::$cpfCounter++;
        } else {
            $user = new MerchantUser();
            $user->cnpj = self::$cnpjCounter++;
        }

        $user->name = 'Test User';
        $user->email = $email;
        $user->password = bcrypt('password');
        $user->save();

        $user->token = $user->createToken('API Token')->plainTextToken;

        return $user;
    }

    public function createWallet($userId, $balance = 100)
    {
        $wallet = new Wallet();
        $wallet->user_id = $userId;
        $wallet->balance = $balance;
        $wallet->save();

        return $wallet;
    }

    public function createCommonUser()
    {
        return $this->createUser('common');
    }

    public function createMerchantUser()
    {
        return $this->createUser('merchant');
    }
}
