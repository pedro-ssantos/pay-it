<?php

namespace AppModules\Wallet\Tests\Feature;

use Mockery;
use Illuminate\Support\Facades\Queue;
use AppModules\Wallet\Tests\WalletTestCase;
use AppModules\Wallet\Services\TransferService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use AppModules\Wallet\Services\TransferStrategyFactory;
use AppModules\Notification\Services\Factories\NotificationStrategyFactory;
use AppModules\Notification\Services\Interfaces\NotificationStrategyInterface;
use AppModules\Authorization\Services\Interfaces\AuthorizationServiceInterface;

class TransferFeatureTest extends WalletTestCase
{
    use RefreshDatabase;

    protected TransferService $sut;
    protected $authorizationServiceMock;
    protected $notificationStrategyFactoryMock;
    protected $notificationStrategyMock;

    protected function setUp(): void
    {
        parent::setUp();
        $transferStrategyFactory =  new TransferStrategyFactory();
        $this->authorizationServiceMock = $this->mock(AuthorizationServiceInterface::class);
        $this->notificationStrategyFactoryMock = Mockery::mock(NotificationStrategyFactory::class);
        $this->notificationStrategyMock = Mockery::mock(NotificationStrategyInterface::class);

        $this->notificationStrategyFactoryMock->shouldReceive('make')
            ->andReturn($this->notificationStrategyMock);

        $this->notificationStrategyMock->shouldReceive('send')
            ->andReturn(true);

        $this->sut = new TransferService($transferStrategyFactory, $this->authorizationServiceMock);

        Queue::fake();
    }

    public function test_common_user_can_transfer_to_another_common_user()
    {
        $this->authorizationServiceMock->shouldReceive('authorize')->andReturn(true);

        $sender = $this->createCommonUser();
        $receiver = $this->createMerchantUser();

        $this->createWallet($sender->id, 200);
        $this->createWallet($receiver->id, 100);

        $result = $this->sut->execute($sender, $receiver, 50);

        $this->assertTrue($result);

        $sender->refresh();
        $receiver->refresh();
        $this->assertEquals(150, $sender->wallet->balance);
        $this->assertEquals(150, $receiver->wallet->balance);
    }

    public function test_common_user_can_transfer_to_merchant_user()
    {
        $this->authorizationServiceMock->shouldReceive('authorize')->andReturn(true);

        $sender = $this->createCommonUser();
        $receiver = $this->createMerchantUser();

        $this->createWallet($sender->id, 300);
        $this->createWallet($receiver->id, 200);

        $result = $this->sut->execute($sender, $receiver, 100);

        $this->assertTrue($result);

        $sender->refresh();
        $receiver->refresh();
        $this->assertEquals(200, $sender->wallet->balance);
        $this->assertEquals(300, $receiver->wallet->balance);
    }

    public function test_transfer_fails_if_insufficient_balance()
    {
        $this->authorizationServiceMock->shouldReceive('authorize')->andReturn(true);

        $sender = $this->createCommonUser();
        $receiver = $this->createMerchantUser();

        $w1 = $this->createWallet($sender->id, 50);
        $w2 = $this->createWallet($receiver->id, 100);

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Insufficient balance');

        $result = $this->sut->execute($sender, $receiver, 100);

        $sender->refresh();
        $receiver->refresh();
        $this->assertEquals(50, $sender->wallet->balance);
        $this->assertEquals(100, $receiver->wallet->balance);
    }

    public function test_merchant_user_cannot_transfer_to_anyone()
    {
        $this->authorizationServiceMock->shouldReceive('authorize')->andReturn(true);

        $sender = $this->createMerchantUser();
        $receiver = $this->createCommonUser();

        $this->createWallet($sender->id, 200);
        $this->createWallet($receiver->id, 100);

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('No valid strategy found for this transfer');

        $this->sut->execute($sender, $receiver, 50);
    }

    public function test_it_allows_transfer_between_users()
    {
        $this->authorizationServiceMock->shouldReceive('authorize')->andReturn(true);

        $sender = $this->createCommonUser();
        $receiver = $this->createMerchantUser();

        $this->createWallet($sender->id, 100);
        $this->createWallet($receiver->id, 50);

        $response = $this->postJson('/api/v1/transfer', [
            'sender_id' => $sender->id,
            'receiver_id' => $receiver->id,
            'amount' => 20.00,
        ]);

        $response->assertStatus(200);
        $this->assertEquals(80.00, $sender->wallet->fresh()->balance);
        $this->assertEquals(70.00, $receiver->wallet->fresh()->balance);
    }

    public function test_transfer_fails_if_unauthorized()
    {
        $this->authorizationServiceMock->shouldReceive('authorize')->andReturn(false);

        $sender = $this->createCommonUser();
        $receiver = $this->createMerchantUser();

        $this->createWallet($sender->id, 200);
        $this->createWallet($receiver->id, 100);

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Unauthorized');

        $this->sut->execute($sender, $receiver, 50);
    }

    public function test_transfer_fails_with_same_sender_and_receiver()
    {
        $user = User::factory()->create(['balance' => 100]);

        $response = $this->postJson('/api/transfer', [
            'sender_id' => $user->id,
            'receiver_id' => $user->id,
            'amount' => 50,
        ]);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['receiver_id']);
    }
}
