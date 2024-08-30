<?php

namespace AppModules\Transaction\Tests\Feature;

use Mockery;
use AppModules\Wallet\Tests\WalletTestCase;
use AppModules\Transaction\Services\DepositService;
use AppModules\Wallet\Exceptions\WalletNotFoundException;
use AppModules\Wallet\Services\Interfaces\WalletServiceInterface;
use AppModules\Wallet\Repositories\Interfaces\WalletRepositoryInterface;

class DepositFeatureTest extends WalletTestCase
{
    protected WalletRepositoryInterface $walletRepositoryMock;
    protected WalletServiceInterface $walletServiceMock;
    protected DepositService $depositService;

    protected function setUp(): void
    {
        parent::setUp();

        $this->walletRepositoryMock = Mockery::mock(WalletRepositoryInterface::class);
        $this->walletServiceMock = Mockery::mock(WalletServiceInterface::class);

        $this->depositService = new DepositService(
            $this->walletRepositoryMock,
            $this->walletServiceMock
        );
    }

    public function test_it_allows_deposit_to_user_wallet()
    {
        $user = $this->createCommonUser();
        $token = $user->createToken('auth_token', ['money-deposit'])->plainTextToken;

        $this->createWallet($user->id, 100);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->postJson('/api/v1/deposit', [
            'receiver_id' => $user->id,
            'amount' => 50.00,
        ]);

        $response->assertStatus(200);
        $this->assertEquals(150.00, $user->wallet->fresh()->balance);
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }
}
