<?php

namespace AppModules\Wallet\Tests\Unit;

use Mockery;
use AppModules\Wallet\Tests\WalletTestCase;
use AppModules\Wallet\Services\BalanceValidator;
use AppModules\Wallet\Exceptions\InsufficientFundsException;
use AppModules\Wallet\Repositories\Interfaces\WalletRepositoryInterface;

class BalanceValidatorServiceTest extends WalletTestCase
{
    protected WalletRepositoryInterface $walletRepository;
    protected BalanceValidator $balanceValidator;

    protected function setUp(): void
    {
        parent::setUp();

        $this->balanceValidator = new BalanceValidator();
    }

    public function test_validate_sufficient_balance()
    {
        $user = $this->createUser();
        $wallet = $this->createWallet($user->id, 100);

        $result = $this->balanceValidator->validate($wallet, 50);

        $this->assertTrue($result);
    }

    public function test_validate_insufficient_balance()
    {
        $user = $this->createUser();
        $wallet = $this->createWallet($user->id, 30.00);

        $this->expectException(InsufficientFundsException::class);
        $this->expectExceptionMessage('Insufficient balance');

        $this->balanceValidator->validate($wallet, 50);
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }
}
