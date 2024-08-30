<?php

namespace AppModules\Transaction\Tests\Unit;

use Mockery;
use Tests\TestCase;
use Illuminate\Support\Facades\DB;
use AppModules\Wallet\Models\Wallet;
use AppModules\Transaction\Services\DepositService;
use AppModules\Wallet\Services\Interfaces\WalletServiceInterface;
use AppModules\Wallet\Repositories\Interfaces\WalletRepositoryInterface;


class DepositServiceTest extends TestCase
{
    protected DepositService $depositService;
    protected WalletRepositoryInterface $walletRepository;
    protected WalletServiceInterface $walletService;

    protected function setUp(): void
    {
        parent::setUp();

        // Mocking interfaces
        $this->walletRepository = Mockery::mock(WalletRepositoryInterface::class);
        $this->walletService = Mockery::mock(WalletServiceInterface::class);

        // Service instance
        $this->depositService = new DepositService(
            $this->walletRepository,
            $this->walletService
        );
    }

    public function test_it_executes_deposit_successfully()
    {
        $receiverId = 1;
        $amount = 100.00;

        $wallet = Mockery::mock(Wallet::class);

        $this->walletRepository->shouldReceive('findByUserIdAndLock')
            ->once()
            ->with($receiverId)
            ->andReturn($wallet);

        $this->walletService->shouldReceive('increaseBalance')
            ->once()
            ->with($wallet, $amount);

        DB::shouldReceive('transaction')
            ->once()
            ->andReturnUsing(function ($callback) {
                $callback();
            });

        $this->depositService->execute($receiverId, $amount);

        // Assert
        // No explicit assertion needed as Mockery will automatically
        // assert the method calls we defined in `shouldReceive`.
        $this->assertTrue(true); // Just to ensure PHPUnit doesn't warn about no assertions.
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }
}
