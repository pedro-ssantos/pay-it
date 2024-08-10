<?php

namespace AppModules\Wallet\Tests\Feature;

use Tests\TestCase;
use AppModules\Wallet\Models\Wallet;
use AppModules\User\Models\CommonUser;
use AppModules\User\Models\MerchantUser;
use AppModules\Wallet\Tests\WalletTestCase;
use AppModules\Wallet\Services\TransferService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use AppModules\Wallet\Services\TransferStrategyFactory;

class TransferFeatureTest extends WalletTestCase
{
    use RefreshDatabase;

    protected TransferService $sut;

    protected function setUp(): void
    {
        parent::setUp();
        $transferStrategyFactory =  new TransferStrategyFactory();
        $this->sut = new TransferService($transferStrategyFactory);
    }

    public function test_common_user_can_transfer_to_another_common_user()
    {
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
        $sender = $this->createCommonUser();
        $receiver = $this->createMerchantUser();

        $w1 = $this->createWallet($sender->id, 50);
        $w2 = $this->createWallet($receiver->id, 100);

        $result = $this->sut->execute($sender, $receiver, 100);

        $this->assertFalse($result);
        $sender->refresh();
        $receiver->refresh();
        $this->assertEquals(50, $sender->wallet->balance);
        $this->assertEquals(100, $receiver->wallet->balance);
    }

    public function test_merchant_user_cannot_transfer_to_anyone()
    {
        $sender = $this->createMerchantUser();
        $receiver = $this->createCommonUser();

        $this->createWallet($sender->id, 200);
        $this->createWallet($receiver->id, 100);

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('No valid strategy found for this transfer');

        $this->sut->execute($sender, $receiver, 50);
    }
}
