<?php

use AppModules\Wallet\Tests\WalletTestCase;
use AppModules\Wallet\Services\WalletService;
use AppModules\Wallet\Repositories\Interfaces\WalletRepositoryInterface;

class WalletServiceTest extends WalletTestCase
{
    protected WalletRepositoryInterface $walletRepository;
    protected WalletService $walletService;

    protected function setUp(): void
    {
        parent::setUp();

        $this->walletRepository = Mockery::mock(WalletRepositoryInterface::class);
        $this->walletService = new WalletService($this->walletRepository);
    }
    public function test_it_can_increase_balance()
    {
        $user = $this->createUser();
        $wallet = $this->createWallet($user->id, 100);

        $this->walletService->increaseBalance($wallet, 50);

        $this->assertEquals(150, $wallet->balance);
    }

    public function test_it_can_decrease_balance()
    {
        $user = $this->createUser();
        $wallet = $this->createWallet($user->id, 100);

        $this->walletService->decreaseBalance($wallet, 40);

        $this->assertEquals(60, $wallet->balance);
    }
}
